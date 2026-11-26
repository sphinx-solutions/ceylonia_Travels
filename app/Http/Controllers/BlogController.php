<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AppliesSeo;
use App\Models\Post;
use Illuminate\View\View;

class BlogController extends Controller
{
    use AppliesSeo;

    public function index(): View
    {
        $locale = app()->getLocale();

        $posts = Post::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->latest('published_at')
            ->paginate(12);

        $this->applySeo(fn ($seo) => $seo->setBlogsIndexSeo());

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

        $this->applySeo(fn ($seo) => $seo->setPostSeo($post));

        return view('blogs.show', [
            'post' => $post,
            'related' => $related,
            'canonical' => route('blogs.show', $post->slug),
            'shareTitle' => $post->title,
        ]);
    }
}
