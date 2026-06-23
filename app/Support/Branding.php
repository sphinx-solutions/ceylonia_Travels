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

        return asset('images/logo-placeholder.svg');
    }

    public static function logoDarkUrl(string $conversion = ''): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('logo_dark') ?: $branding->getFirstMedia('logo');

        if ($media) {
            return $conversion ? $media->getUrl($conversion) : $media->getUrl();
        }

        return asset('images/logo-placeholder.svg');
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

        return asset('images/logo-placeholder.svg');
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

        return asset('images/logo-placeholder.svg');
    }

    public static function footerLogoUrl(): string
    {
        $branding = SiteBranding::instance();
        $media = $branding->getFirstMedia('footer_logo') ?: $branding->getFirstMedia('logo');

        if ($media) {
            return $media->getUrl('thumb');
        }

        return asset('images/logo-placeholder.svg');
    }
}
