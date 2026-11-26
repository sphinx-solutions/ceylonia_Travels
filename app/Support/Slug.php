<?php

namespace App\Support;

use Illuminate\Support\Str;

class Slug
{
    /**
     * Build a URL-safe slug. Keeps letters/numbers (including Unicode),
     * spaces become hyphens; empty results fall back to a short hash.
     */
    public static function make(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return substr(sha1((string) microtime(true)), 0, 10);
        }

        $slug = Str::of($value)
            ->lower()
            ->replaceMatches('/\s+/u', '-')
            ->replaceMatches('/[^\p{L}\p{N}\-]+/u', '')
            ->replaceMatches('/-+/', '-')
            ->trim('-')
            ->toString();

        if ($slug === '') {
            return substr(sha1($value), 0, 10);
        }

        return $slug;
    }
}
