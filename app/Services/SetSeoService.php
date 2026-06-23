<?php

namespace App\Services;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use App\Models\TourPackage;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SetSeoService
{
    public function __construct(
        public Collection $settings,
        public ?string $logoUrl = null,
    ) {}

    public function setHomePageSeo(): void
    {
        $siteTitle = $this->siteName();
        $siteDescription = $this->siteDescription();
        $logo = $this->logoUrl();

        $this->applyPageSeo(
            title: $siteTitle,
            description: $siteDescription,
            url: url('/'),
            image: $logo,
            type: 'website',
        );

        JsonLd::setType('WebSite');
        JsonLd::addValue('@id', url('/'));
        JsonLd::addValue('url', url('/'));
        JsonLd::addValue('name', $siteTitle);
        JsonLd::addValue('publisher', $this->organizationSchema($siteTitle, $logo));
    }

    public function setPostSeo(Post $post): void
    {
        $post->loadMissing('media');

        $url = route('blogs.show', $post->slug);
        $title = $post->meta_title ?: $post->title;
        $description = $this->postDescription($post);
        $bannerMedia = $post->getFirstMedia('banner');
        $image = $bannerMedia ? $bannerMedia->getUrl('og') : $this->logoUrl();

        $this->applyPageSeo(
            title: $title.' | '.$this->siteName(),
            description: $description,
            url: $url,
            image: $image,
            type: 'article',
        );

        OpenGraph::addProperty('article:published_time', optional($post->published_at ?? $post->created_at)?->toIso8601String());
        OpenGraph::addProperty('article:author', $post->author ?: $this->siteName());

        if ($post->schema_data) {
            JsonLd::setRaw($post->schema_data);

            return;
        }

        JsonLd::setType('BlogPosting');
        JsonLd::addValue('headline', $title);
        JsonLd::addValue('datePublished', optional($post->published_at ?? $post->created_at)?->toIso8601String());
        JsonLd::addValue('dateModified', optional($post->updated_at)?->toIso8601String());
        JsonLd::addValue('author', [
            '@type' => 'Person',
            'name' => $post->author ?: $this->siteName(),
        ]);
        JsonLd::addValue('publisher', $this->organizationSchema($this->siteName(), $this->logoUrl()));
        JsonLd::addValue('mainEntityOfPage', ['@type' => 'WebPage', '@id' => $url]);
    }

    public function setPageSeo(Page $page): void
    {
        $title = $page->meta_title ?: $page->title;
        $description = $page->meta_description ?: Str::limit(strip_tags($page->content ?? ''), 160);

        $this->applyPageSeo(
            title: $title.' | '.$this->siteName(),
            description: $description,
            url: url()->current(),
            image: $this->logoUrl(),
            type: 'website',
        );

        JsonLd::setType('WebPage');
        JsonLd::addValue('name', $title);
    }

    public function setAlbumsIndexSeo(): void
    {
        $title = __('Photo Albums').' | '.$this->siteName();
        $description = __('Explore our Sri Lanka travel photo galleries — wildlife, beaches, culture, and unforgettable moments.');

        $this->applyPageSeo(
            title: $title,
            description: $description,
            url: route('albums.index'),
            image: $this->logoUrl(),
            type: 'website',
        );

        JsonLd::setType('CollectionPage');
        JsonLd::addValue('name', __('Photo Albums'));
    }

    public function setAlbumSeo(Album $album): void
    {
        $album->loadMissing('media');

        $title = $album->name.' | '.$this->siteName();
        $description = Str::limit(strip_tags($album->description ?: ''), 160)
            ?: __('Photo gallery: :name', ['name' => $album->name]);
        $cover = $album->getFirstMedia('cover') ?: $album->getFirstMedia('files');
        $image = $cover ? $cover->getUrl('og') : $this->logoUrl();
        $url = route('albums.show', $album->slug);

        $this->applyPageSeo(
            title: $title,
            description: $description,
            url: $url,
            image: $image,
            type: 'website',
        );

        JsonLd::setType('ImageGallery');
        JsonLd::addValue('name', $album->name);
    }

    public function setPackagesIndexSeo(): void
    {
        $title = __('Top Packages').' | '.$this->siteName();
        $description = __('Handpicked Sri Lanka tour packages for European travellers — culture, nature, and beach escapes.');

        $this->applyPageSeo(
            title: $title,
            description: $description,
            url: route('packages.index'),
            image: $this->logoUrl(),
            type: 'website',
        );

        JsonLd::setType('CollectionPage');
        JsonLd::addValue('name', __('Top Packages'));
    }

    public function setPackageSeo(TourPackage $package): void
    {
        $package->loadMissing('media');

        $title = ($package->meta_title ?: $package->title).' | '.$this->siteName();
        $description = $package->meta_description ?: Str::limit(strip_tags($package->excerpt ?: $package->content ?: ''), 160);
        $cover = $package->getFirstMedia('cover');
        $image = $cover ? $cover->getUrl('og') : $this->logoUrl();
        $url = route('packages.show', $package->slug);

        $this->applyPageSeo(
            title: $title,
            description: $description,
            url: $url,
            image: $image,
            type: 'website',
        );

        JsonLd::setType('TouristTrip');
        JsonLd::addValue('name', $package->title);
        if ($package->destination) {
            JsonLd::addValue('touristType', $package->destination);
        }
    }

    public function setTestimonialsSeo(): void
    {
        $title = __('Testimonials').' | '.$this->siteName();
        $description = __('Read what European travellers say about their Sri Lanka journeys with :name.', ['name' => $this->siteName()]);

        $this->applyPageSeo(
            title: $title,
            description: $description,
            url: route('testimonials.index'),
            image: $this->logoUrl(),
            type: 'website',
        );
    }

    public function setBlogsIndexSeo(): void
    {
        $title = __('Blogs').' | '.$this->siteName();
        $description = __('Travel stories, tips, and inspiration for your Sri Lanka adventure.');

        $this->applyPageSeo(
            title: $title,
            description: $description,
            url: route('blogs.index'),
            image: $this->logoUrl(),
            type: 'website',
        );

        JsonLd::setType('Blog');
        JsonLd::addValue('name', __('Blogs'));
    }

    public function setContactSeo(): void
    {
        $description = $this->getSetting('contact_intro')
            ?: __('Contact :name to plan your Sri Lanka holiday. We reply within 24 hours.', ['name' => $this->siteName()]);

        $this->applyPageSeo(
            title: __('Contact Us').' | '.$this->siteName(),
            description: Str::limit(strip_tags($description), 160),
            url: route('contact'),
            image: $this->logoUrl(),
            type: 'website',
        );

        JsonLd::setType('ContactPage');
    }

    protected function applyPageSeo(
        string $title,
        string $description,
        string $url,
        string $image,
        string $type = 'website',
        array $keywords = [],
    ): void {
        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setCanonical($url);

        if ($keywords !== []) {
            SEOMeta::addKeyword($keywords);
        }

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($url);
        OpenGraph::setType($type);
        OpenGraph::addImage($image);

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);
        TwitterCard::setImage($image);
        TwitterCard::setType('summary_large_image');

        JsonLd::setTitle($title);
        JsonLd::setDescription($description);
        JsonLd::addImage($image);
        JsonLd::setUrl($url);
    }

    protected function postDescription(Post $post): string
    {
        if ($post->meta_description) {
            return $post->meta_description;
        }

        return Str::limit(strip_tags($post->content ?? ''), 160);
    }

    protected function organizationSchema(string $name, string $logo): array
    {
        return [
            '@type' => 'TravelAgency',
            'name' => $name,
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo,
            ],
            'sameAs' => array_values(array_filter([
                $this->getSetting('facebook'),
                $this->getSetting('instagram'),
                $this->getSetting('youtube'),
            ])),
        ];
    }

    protected function siteName(): string
    {
        return $this->getSetting('site_name') ?: config('site.name');
    }

    protected function siteDescription(): string
    {
        return $this->getSetting('site_description') ?: config('site.description');
    }

    protected function logoUrl(): string
    {
        return $this->logoUrl ?? asset('images/logo-placeholder.svg');
    }

    protected function getSetting(string $key, ?string $default = null): ?string
    {
        $locale = app()->getLocale();

        return $this->settings
            ->where('key', $key)
            ->where('locale', $locale)
            ->first()?->value
            ?? $this->settings->where('key', $key)->where('locale', 'en')->first()?->value
            ?? $default;
    }
}
