<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Post;
use App\Models\TourPackage;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Throwable;

class SitemapController extends Controller
{
    public function index(): Response
    {
        try {
            $urls = $this->buildUrls();
        } catch (Throwable $e) {
            report($e);
            $urls = [[
                'loc' => url('/'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ]];
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @return list<array{loc: string, lastmod: string, changefreq: string, priority: string}>
     */
    protected function buildUrls(): array
    {
        $urls = [
            $this->entry(url('/'), now(), 'daily', '1.0'),
            $this->entry(route('packages.index'), now(), 'weekly', '0.9'),
            $this->entry(route('blogs.index'), now(), 'weekly', '0.8'),
            $this->entry(route('albums.index'), now(), 'monthly', '0.6'),
            $this->entry(route('about'), now(), 'monthly', '0.6'),
            $this->entry(route('testimonials.index'), now(), 'monthly', '0.5'),
            $this->entry(route('contact'), now(), 'monthly', '0.5'),
        ];

        TourPackage::query()->published()->orderBy('id')->chunkById(100, function ($packages) use (&$urls) {
            foreach ($packages as $package) {
                if (! $this->validSlug($package->slug)) {
                    continue;
                }

                try {
                    $urls[] = $this->entry(
                        route('packages.show', $package->slug),
                        $package->updated_at,
                        'weekly',
                        '0.9'
                    );
                } catch (Throwable) {
                    continue;
                }
            }
        });

        Post::query()->published()->orderBy('id')->chunkById(100, function ($posts) use (&$urls) {
            foreach ($posts as $post) {
                if (! $this->validSlug($post->slug)) {
                    continue;
                }

                try {
                    $urls[] = $this->entry(
                        route('blogs.show', $post->slug),
                        $post->updated_at,
                        'weekly',
                        '0.7'
                    );
                } catch (Throwable) {
                    continue;
                }
            }
        });

        Album::query()->published()->orderBy('id')->chunkById(100, function ($albums) use (&$urls) {
            foreach ($albums as $album) {
                if (! $this->validSlug($album->slug)) {
                    continue;
                }

                try {
                    $urls[] = $this->entry(
                        route('albums.show', $album->slug),
                        $album->updated_at,
                        'monthly',
                        '0.6'
                    );
                } catch (Throwable) {
                    continue;
                }
            }
        });

        return $urls;
    }

    /**
     * @return array{loc: string, lastmod: string, changefreq: string, priority: string}
     */
    protected function entry(string $loc, ?Carbon $lastmod, string $changefreq, string $priority): array
    {
        return [
            'loc' => $loc,
            'lastmod' => ($lastmod ?? now())->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    protected function validSlug(?string $slug): bool
    {
        return filled($slug) && ! str_contains((string) $slug, ' ');
    }
}
