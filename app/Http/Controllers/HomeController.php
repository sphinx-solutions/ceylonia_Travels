<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\TourPackage;
use App\Services\SetSeoService;
use App\Support\Branding;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $featuredPackages = TourPackage::query()
            ->published()
            ->featured()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $testimonials = Testimonial::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $albums = Album::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        $this->seo()->setHomePageSeo();

        return view('home', compact('featuredPackages', 'latestPosts', 'testimonials', 'albums'));
    }

    public function about(): View
    {
        $locale = app()->getLocale();
        $page = Page::query()->where('slug', 'about')->forLocale($locale)->first()
            ?? Page::query()->where('slug', 'about')->forLocale('en')->first();

        if ($page) {
            $this->seo()->setPageSeo($page);
        } else {
            $this->seo()->setPageSeo(new Page([
                'title' => __('About Us'),
                'content' => '',
            ]));
        }

        return view('about', compact('page'));
    }

    public function testimonials(): View
    {
        $locale = app()->getLocale();

        $testimonials = Testimonial::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(12);

        $this->seo()->setTestimonialsSeo();

        return view('testimonials', compact('testimonials'));
    }

    protected function seo(): SetSeoService
    {
        return new SetSeoService(
            Setting::query()->whereIn('locale', [app()->getLocale(), 'en'])->get(),
            Branding::logoUrl('og'),
        );
    }
}
