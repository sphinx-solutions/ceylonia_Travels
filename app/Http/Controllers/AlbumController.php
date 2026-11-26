<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AppliesSeo;
use App\Models\Album;
use Illuminate\View\View;

class AlbumController extends Controller
{
    use AppliesSeo;

    public function index(): View
    {
        $locale = app()->getLocale();

        $albums = Album::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(12);

        $this->applySeo(fn ($seo) => $seo->setAlbumsIndexSeo());

        return view('albums.index', compact('albums'));
    }

    public function show(Album $album): View
    {
        abort_unless($album->is_published, 404);

        $album->load('media');

        $this->applySeo(fn ($seo) => $seo->setAlbumSeo($album));

        return view('albums.show', [
            'album' => $album,
            'canonical' => route('albums.show', $album->slug),
            'shareTitle' => $album->name,
        ]);
    }
}
