<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use App\Models\TourPackage;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $posts = Post::query()->published()->latest('updated_at')->get(['slug', 'updated_at']);
        $albums = Album::query()->published()->latest('updated_at')->get(['slug', 'updated_at']);
        $packages = TourPackage::query()->published()->latest('updated_at')->get(['slug', 'updated_at']);
        $pages = Page::query()->get(['slug', 'locale', 'updated_at']);

        return response()->view('sitemap', compact('posts', 'albums', 'packages', 'pages'))
            ->header('Content-Type', 'application/xml');
    }
}
