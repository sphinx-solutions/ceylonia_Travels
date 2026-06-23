<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TourPackage;
use App\Services\SetSeoService;
use App\Support\Branding;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $packages = TourPackage::query()
            ->published()
            ->forLocale($locale)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(12);

        $this->seo()->setPackagesIndexSeo();

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

        $this->seo()->setPackageSeo($package);

        return view('packages.show', compact('package', 'related'));
    }

    protected function seo(): SetSeoService
    {
        return new SetSeoService(
            Setting::query()->whereIn('locale', [app()->getLocale(), 'en'])->get(),
            Branding::logoUrl('og'),
        );
    }
}
