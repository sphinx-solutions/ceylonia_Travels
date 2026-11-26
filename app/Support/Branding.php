<?php

namespace App\Support;

use App\Models\SiteBranding;

class Branding
{
    public static function logoUrl(string $conversion = ''): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('logo');

        if ($media) {
            return $conversion ? $media->getUrl($conversion) : $media->getUrl();
        }

        return asset(config('site.logo', 'images/logo.svg'));
    }

    public static function logoDarkUrl(string $conversion = ''): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('logo_dark') ?: $branding->getFirstMedia('logo');

        if ($media) {
            return $conversion ? $media->getUrl($conversion) : $media->getUrl();
        }

        return asset(config('site.logo', 'images/logo.svg'));
    }

    public static function faviconUrl(): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('favicon') ?: $branding->getFirstMedia('logo');

        if ($media) {
            return $media->hasGeneratedConversion('favicon-32')
                ? $media->getUrl('favicon-32')
                : $media->getUrl();
        }

        return asset('images/favicon.svg');
    }

    public static function appleTouchIconUrl(): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('favicon') ?: $branding->getFirstMedia('logo');

        if ($media) {
            return $media->hasGeneratedConversion('favicon-180')
                ? $media->getUrl('favicon-180')
                : $media->getUrl();
        }

        return asset('images/logo-mark.svg');
    }

    public static function footerLogoUrl(): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('footer_logo') ?: $branding->getFirstMedia('logo');

        if ($media) {
            return $media->getUrl('thumb');
        }

        return asset(config('site.logo', 'images/logo.svg'));
    }

    /**
     * Prefer a dedicated OG asset, then logo og conversion, then logo, then config fallback.
     */
    public static function ogImageUrl(): string
    {
        $configured = config('site.og_image');
        if ($configured && is_string($configured) && file_exists(public_path($configured))) {
            return asset($configured);
        }

        $branding = SiteBranding::instance();
        $logo = $branding->getFirstMedia('logo');

        if ($logo) {
            if ($logo->hasGeneratedConversion('og')) {
                return $logo->getUrl('og');
            }

            return $logo->getUrl();
        }

        return asset(config('site.logo', 'images/logo.svg'));
    }
}
