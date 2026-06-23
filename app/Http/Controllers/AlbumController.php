<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Setting;
use App\Services\SetSeoService;
use App\Support\Branding;
use Illuminate\View\View;

class AlbumController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $albums = Album::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(12);

        $this->seo()->setAlbumsIndexSeo();

        return view('albums.index', compact('albums'));
    }

    public function show(Album $album): View
    {
        abort_unless($album->is_published, 404);

        $album->load('media');

        $this->seo()->setAlbumSeo($album);

        return view('albums.show', compact('album'));
    }

    protected function seo(): SetSeoService
    {
        return new SetSeoService(
            Setting::query()->whereIn('locale', [app()->getLocale(), 'en'])->get(),
            Branding::logoUrl('og'),
        );
    }
}
