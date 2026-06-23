<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function switch(string $locale): RedirectResponse
    {
        $available = array_keys(config('locales.available', []));

        if (! in_array($locale, $available, true)) {
            abort(404);
        }

        session([config('locales.session_key', 'app_locale') => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
