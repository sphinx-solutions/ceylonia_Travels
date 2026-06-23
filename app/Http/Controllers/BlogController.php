<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Setting;
use App\Services\SetSeoService;
use App\Support\Branding;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $posts = Post::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->latest('published_at')
            ->paginate(12);

        $seo = new SetSeoService(
            Setting::query()->whereIn('locale', [$locale, 'en'])->get(),
            Branding::logoUrl('og'),
        );

        $seo->setBlogsIndexSeo();

        return view('blogs.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->status === 'published', 404);

        $post->load('media');

        $related = Post::query()
            ->published()
            ->forLocale($post->locale)
            ->where('id', '!=', $post->id)
            ->with('media')
            ->latest('published_at')
            ->limit(3)
            ->get();

        $seo = new SetSeoService(
            Setting::query()->whereIn('locale', [app()->getLocale(), 'en'])->get(),
            Branding::logoUrl('og'),
        );
        $seo->setPostSeo($post);

        return view('blogs.show', compact('post', 'related'));
    }
}
