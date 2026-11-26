<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AppliesSeo;
use App\Models\TourPackage;
use Illuminate\View\View;

class PackageController extends Controller
{
    use AppliesSeo;

    public function index(): View
    {
        $locale = app()->getLocale();

        $packages = TourPackage::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(12);

        $this->applySeo(fn ($seo) => $seo->setPackagesIndexSeo());

        return view('packages.index', compact('packages'));
    }

    public function show(TourPackage $package): View
    {
        abort_unless($package->is_published, 404);

        $package->load('media');

        $related = TourPackage::query()
            ->published()
            ->forLocale($package->locale)
            ->where('id', '!=', $package->id)
            ->with('media')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $this->applySeo(fn ($seo) => $seo->setPackageSeo($package));

        return view('packages.show', [
            'package' => $package,
            'related' => $related,
            'canonical' => route('packages.show', $package->slug),
            'shareTitle' => $package->title,
        ]);
    }
}
