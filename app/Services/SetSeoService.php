<?php

namespace App\Services;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\TourPackage;
use App\Support\Branding;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SetSeoService
{
    public function __construct(
        public Collection $settings,
        public ?string $logoUrl = null,
    ) {}

    public static function make(): self
    {
        $locale = app()->getLocale();

        return new self(
            Setting::query()->whereIn('locale', [$locale, 'en'])->get(),
            Branding::ogImageUrl(),
        );
    }

    public function setHomePageSeo(): void
    {
        $this->safe(function () {
            $siteTitle = $this->siteName();
            $siteDescription = $this->plainDescription($this->siteDescription());
            $image = $this->homeOgImage();
            $url = url('/');

            $this->applyPageSeo(
                title: $siteTitle,
                description: $siteDescription,
                url: $url,
                image: $image,
                type: 'website',
                appendSiteName: false,
                imageMeta: true,
            );

            JsonLd::setType('WebSite');
            JsonLd::addValue('@id', $url);
            JsonLd::addValue('url', $url);
            JsonLd::addValue('name', $siteTitle);
            JsonLd::addValue('description', $siteDescription);
            JsonLd::addValue('publisher', $this->travelAgencySchema());
            JsonLd::addValue('inLanguage', array_keys(config('locales.available', ['en' => []])));
        });
    }

    public function setPostSeo(Post $post): void
    {
        $this->safe(function () use ($post) {
            $post->loadMissing('media');

            $url = route('blogs.show', $post->slug);
            $title = $post->meta_title ?: $post->title;
            $description = $this->plainDescription(
                $post->meta_description ?: strip_tags($post->content ?? '')
            );
            $banner = $post->getFirstMedia('banner');
            $image = $banner ? $banner->getUrl('og') : $this->logoUrl();
            $author = $this->realAuthor($post->author);

            $this->applyPageSeo(
                title: $title,
                description: $description,
                url: $url,
                image: $image,
                type: 'article',
            );

            OpenGraph::addProperty('article:published_time', optional($post->published_at ?? $post->created_at)?->toIso8601String());
            OpenGraph::addProperty('article:author', $author);
            OpenGraph::addProperty('article:modified_time', optional($post->updated_at)?->toIso8601String());

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
                'name' => $author,
            ]);
            JsonLd::addValue('publisher', $this->travelAgencySchema());
            JsonLd::addValue('mainEntityOfPage', [
                '@type' => 'WebPage',
                '@id' => $url,
            ]);
            JsonLd::addValue('inLanguage', $post->locale ?: app()->getLocale());
        });
    }

    public function setPageSeo(Page $page, string $schemaType = 'WebPage'): void
    {
        $this->safe(function () use ($page, $schemaType) {
            $title = $page->meta_title ?: $page->title;
            $description = $this->plainDescription(
                $page->meta_description ?: strip_tags($page->content ?? '')
            );
            $url = $this->pageCanonical($page);

            $this->applyPageSeo(
                title: $title,
                description: $description ?: $this->plainDescription($this->siteDescription()),
                url: $url,
                image: $this->logoUrl(),
                type: 'website',
            );

            JsonLd::setType($schemaType);
            JsonLd::addValue('name', $title);
            JsonLd::addValue('url', $url);
        });
    }

    public function setAboutSeo(?Page $page = null): void
    {
        if ($page) {
            $this->setPageSeo($page, 'AboutPage');

            return;
        }

        $this->safe(function () {
            $this->applyPageSeo(
                title: __('About Us'),
                description: $this->plainDescription(
                    __('Ceyloania Travels is a specialist Sri Lanka tour operator serving European travellers.')
                ),
                url: route('about'),
                image: $this->logoUrl(),
            );
            JsonLd::setType('AboutPage');
            JsonLd::addValue('name', __('About Us'));
        });
    }

    public function setAlbumsIndexSeo(): void
    {
        $this->safe(function () {
            $this->applyPageSeo(
                title: __('Photo Albums'),
                description: $this->plainDescription(
                    __('Explore our Sri Lanka travel photo galleries — wildlife, beaches, culture, and unforgettable moments.')
                ),
                url: route('albums.index'),
                image: $this->logoUrl(),
            );

            JsonLd::setType('CollectionPage');
            JsonLd::addValue('name', __('Photo Albums'));
        });
    }

    public function setAlbumSeo(Album $album): void
    {
        $this->safe(function () use ($album) {
            $album->loadMissing('media');

            $title = $album->meta_title ?? $album->name;
            $description = $this->plainDescription(
                ($album->meta_description ?? null)
                    ?: strip_tags($album->description ?: '')
                    ?: __('Photo gallery: :name', ['name' => $album->name])
            );
            $cover = $album->getFirstMedia('cover') ?: $album->getFirstMedia('files');
            $image = $cover
                ? ($cover->hasGeneratedConversion('og') ? $cover->getUrl('og') : $cover->getUrl())
                : $this->logoUrl();
            $url = route('albums.show', $album->slug);

            $this->applyPageSeo(
                title: $title,
                description: $description,
                url: $url,
                image: $image,
            );

            if (! empty($album->schema_data ?? null)) {
                JsonLd::setRaw($album->schema_data);

                return;
            }

            JsonLd::setType('ImageGallery');
            JsonLd::addValue('name', $album->name);
            JsonLd::addValue('url', $url);
        });
    }

    public function setPackagesIndexSeo(): void
    {
        $this->safe(function () {
            $this->applyPageSeo(
                title: __('Top Packages'),
                description: $this->plainDescription(
                    __('Handpicked Sri Lanka tour packages for European travellers — culture, nature, and beach escapes.')
                ),
                url: route('packages.index'),
                image: $this->logoUrl(),
            );

            JsonLd::setType('CollectionPage');
            JsonLd::addValue('name', __('Top Packages'));
        });
    }

    public function setPackageSeo(TourPackage $package): void
    {
        $this->safe(function () use ($package) {
            $package->loadMissing('media');

            $title = $package->meta_title ?: $package->title;
            $description = $this->plainDescription(
                $package->meta_description
                    ?: strip_tags($package->excerpt ?: $package->content ?: '')
            );
            $cover = $package->getFirstMedia('cover');
            $image = $cover ? $cover->getUrl('og') : $this->logoUrl();
            $url = route('packages.show', $package->slug);

            $keywords = array_values(array_filter([
                $package->destination,
                $package->duration_days ? $package->duration_days.' days' : null,
            ]));

            $this->applyPageSeo(
                title: $title,
                description: $description,
                url: $url,
                image: $image,
                keywords: $keywords,
            );

            if (! empty($package->schema_data ?? null)) {
                JsonLd::setRaw($package->schema_data);

                return;
            }

            JsonLd::setType('TouristTrip');
            JsonLd::addValue('name', $package->title);
            JsonLd::addValue('description', $description);
            JsonLd::addValue('url', $url);
            JsonLd::addValue('image', $image);

            if ($package->destination) {
                JsonLd::addValue('touristType', $package->destination);
                JsonLd::addValue('itinerary', [
                    '@type' => 'Place',
                    'name' => $package->destination,
                ]);
            }

            if ($package->duration_days) {
                JsonLd::addValue('duration', 'P'.$package->duration_days.'D');
            }

            if ($package->price_from) {
                JsonLd::addValue('offers', [
                    '@type' => 'Offer',
                    'price' => (float) $package->price_from,
                    'priceCurrency' => $package->currency ?: 'EUR',
                    'availability' => 'https://schema.org/InStock',
                    'url' => $url,
                ]);
            }

            JsonLd::addValue('provider', $this->travelAgencySchema());
        });
    }

    public function setTestimonialsSeo(): void
    {
        $this->safe(function () {
            $this->applyPageSeo(
                title: __('Testimonials'),
                description: $this->plainDescription(
                    __('Read what European travellers say about their Sri Lanka journeys with :name.', [
                        'name' => $this->siteName(),
                    ])
                ),
                url: route('testimonials.index'),
                image: $this->logoUrl(),
            );

            JsonLd::setType('WebPage');
            JsonLd::addValue('name', __('Testimonials'));
        });
    }

    public function setBlogsIndexSeo(): void
    {
        $this->safe(function () {
            $this->applyPageSeo(
                title: __('Blogs'),
                description: $this->plainDescription(
                    __('Travel stories, tips, and inspiration for your Sri Lanka adventure.')
                ),
                url: route('blogs.index'),
                image: $this->logoUrl(),
            );

            JsonLd::setType('Blog');
            JsonLd::addValue('name', __('Blogs'));
            JsonLd::addValue('publisher', $this->travelAgencySchema());
        });
    }

    public function setContactSeo(): void
    {
        $this->safe(function () {
            $description = $this->plainDescription(
                $this->getSetting('contact_intro')
                    ?: __('Contact :name to plan your Sri Lanka holiday. We reply within 24 hours.', [
                        'name' => $this->siteName(),
                    ])
            );

            $this->applyPageSeo(
                title: __('Contact Us'),
                description: $description,
                url: route('contact'),
                image: $this->logoUrl(),
            );

            JsonLd::setType('ContactPage');
            JsonLd::addValue('name', __('Contact Us'));
            JsonLd::addValue('url', route('contact'));
        });
    }

    public function setNoIndex(): void
    {
        $this->safe(function () {
            SEOMeta::setRobots('noindex, nofollow');
        });
    }

    protected function applyPageSeo(
        string $title,
        string $description,
        string $url,
        string $image,
        string $type = 'website',
        array $keywords = [],
        bool $appendSiteName = true,
        bool $imageMeta = false,
    ): void {
        $description = $this->plainDescription($description);
        $fullTitle = $appendSiteName
            ? $this->pageTitle($title)
            : $title;

        SEOMeta::setTitle($fullTitle, false);
        SEOMeta::setDescription($description);
        SEOMeta::setCanonical($url);
        SEOMeta::setRobots('index, follow');

        if ($keywords !== []) {
            SEOMeta::addKeyword($keywords);
        }

        OpenGraph::setTitle($fullTitle);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl($url);
        OpenGraph::setType($type);
        OpenGraph::setSiteName($this->siteName());

        if ($imageMeta) {
            OpenGraph::addImage($image, [
                'width' => config('site.og_width', 1200),
                'height' => config('site.og_height', 630),
                'alt' => $this->siteName(),
            ]);
        } else {
            OpenGraph::addImage($image, [
                'width' => config('site.og_width', 1200),
                'height' => config('site.og_height', 630),
                'alt' => $title,
            ]);
        }

        TwitterCard::setType('summary_large_image');
        TwitterCard::setTitle($fullTitle);
        TwitterCard::setDescription($description);
        TwitterCard::setImage($image);

        JsonLd::setTitle($fullTitle);
        JsonLd::setDescription($description);
        JsonLd::addImage($image);
        JsonLd::setUrl($url);
    }

    protected function pageTitle(string $pageName): string
    {
        $site = $this->siteName();

        if (strcasecmp(trim($pageName), $site) === 0) {
            return $site;
        }

        return trim($pageName).' | '.$site;
    }

    protected function plainDescription(?string $value): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $value)) ?? '');

        return Str::limit($text, 160, '');
    }

    protected function realAuthor(?string $author): string
    {
        $author = trim((string) $author);

        if ($author === '' || strcasecmp($author, 'Admin') === 0) {
            return $this->siteName().' Travel Specialist';
        }

        return $author;
    }

    protected function travelAgencySchema(): array
    {
        $sameAs = array_values(array_filter([
            $this->getSetting('facebook'),
            $this->getSetting('instagram'),
            $this->getSetting('youtube'),
            $this->getSetting('tiktok'),
            $this->getSetting('twitter'),
        ]));

        $schema = [
            '@type' => 'TravelAgency',
            'name' => config('site.publisher_name') ?: $this->siteName(),
            'url' => url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $this->logoUrl(),
            ],
            'email' => config('site.contact_email'),
            'areaServed' => config('site.region', 'Sri Lanka'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => config('site.contact_email'),
                'availableLanguage' => array_values(array_map(
                    fn ($info) => $info['label'] ?? null,
                    config('locales.available', [])
                )),
            ],
        ];

        if ($sameAs !== []) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }

    protected function pageCanonical(Page $page): string
    {
        return match ($page->slug) {
            'about' => route('about'),
            'contact' => route('contact'),
            default => url()->current(),
        };
    }

    protected function homeOgImage(): string
    {
        $configured = config('site.og_image');

        if ($configured && file_exists(public_path($configured))) {
            return asset($configured);
        }

        return $this->logoUrl();
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
        return $this->logoUrl ?: Branding::ogImageUrl();
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

    protected function safe(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable $e) {
            Log::warning('SEO setup failed: '.$e->getMessage(), [
                'exception' => $e::class,
            ]);
        }
    }
}
