<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('locales.available', ['en' => []]));
        $default = config('app.locale', config('locales.default', 'en'));
        $sessionKey = config('locales.session_key', 'app_locale');

        $locale = $request->session()->get($sessionKey);

        if ($request->routeIs('blogs.show')) {
            $post = $request->route('post');

            if ($post instanceof Post && filled($post->locale) && in_array($post->locale, $available, true)) {
                $locale = $post->locale;
                $request->session()->put($sessionKey, $locale);
            }
        }

        if (! is_string($locale) || ! in_array($locale, $available, true)) {
            $locale = $default;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
