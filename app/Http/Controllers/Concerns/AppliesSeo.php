<?php

namespace App\Http\Controllers\Concerns;

use App\Services\SetSeoService;
use Closure;
use Throwable;

trait AppliesSeo
{
    protected function seo(): SetSeoService
    {
        return SetSeoService::make();
    }

    protected function applySeo(Closure $callback): void
    {
        try {
            $callback($this->seo());
        } catch (Throwable $e) {
            report($e);
        }
    }
}
