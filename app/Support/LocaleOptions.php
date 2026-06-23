<?php

namespace App\Support;

class LocaleOptions
{
    /**
     * @return array<string, string>
     */
    public static function forSelect(): array
    {
        $options = [];

        foreach (config('locales.available', []) as $code => $info) {
            $options[$code] = ($info['label'] ?? $code).' ('.($info['native'] ?? $code).')';
        }

        return $options;
    }
}
