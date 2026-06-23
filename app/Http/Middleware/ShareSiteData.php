<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Models\SiteBranding;
use App\Support\Branding;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareSiteData
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = app()->getLocale();

        if (! Schema::hasTable('settings')) {
            View::share('siteSettings', collect());
            View::share('siteBranding', null);
            View::share('siteName', config('site.name'));
            View::share('siteTagline', config('site.tagline'));
            View::share('logoUrl', Branding::logoUrl());
            View::share('logoDarkUrl', Branding::logoDarkUrl());
            View::share('faviconUrl', Branding::faviconUrl());
            View::share('availableLocales', config('locales.available', []));

            return $next($request);
        }

        $settings = Setting::query()
            ->whereIn('locale', [$locale, 'en'])
            ->get();

        $branding = Schema::hasTable('site_brandings') ? SiteBranding::instance() : null;

        View::share('siteSettings', $settings);
        View::share('siteBranding', $branding);
        View::share('siteName', $settings->where('key', 'site_name')->where('locale', $locale)->first()?->value
            ?? $settings->where('key', 'site_name')->where('locale', 'en')->first()?->value
            ?? config('site.name'));
        View::share('siteTagline', config('site.tagline'));
        View::share('logoUrl', Branding::logoUrl());
        View::share('logoDarkUrl', Branding::logoDarkUrl());
        View::share('faviconUrl', Branding::faviconUrl());
        View::share('availableLocales', config('locales.available', []));

        return $next($request);
    }
}
