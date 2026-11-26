@php
    $currentLocale = app()->getLocale();
    $availableLocales = $availableLocales ?? config('locales.available', []);
    $siteName = $siteName ?? config('site.name');
    $siteTagline = $siteTagline ?? config('site.tagline');
    $logoUrl = $logoUrl ?? \App\Support\Branding::logoUrl();
    $faviconUrl = $faviconUrl ?? \App\Support\Branding::faviconUrl();
    $hreflangUrl = request()->url(); // clean path, no query / UTM
@endphp
<!DOCTYPE html>
<html lang="{{ $currentLocale }}" dir="{{ $availableLocales[$currentLocale]['dir'] ?? 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ \App\Support\Branding::appleTouchIconUrl() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ceyloania.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @foreach ($availableLocales as $code => $info)
        <link rel="alternate" hreflang="{{ $code }}" href="{{ $hreflangUrl }}?hl={{ $code }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangUrl }}?hl={{ config('locales.default', 'en') }}">
    {!! SEO::generate() !!}
    @stack('head')
</head>
<body class="ct-body">
    <header class="ct-header" id="site-header">
        <div class="ct-container ct-header-inner">
            <a href="{{ route('home') }}" class="ct-brand">
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="160" height="48">
                <div class="ct-brand-text">
                    <p class="ct-brand-name">{{ $siteName }}</p>
                    <p class="ct-brand-tagline">{{ $siteTagline }}</p>
                </div>
            </a>

            <nav id="site-nav" class="ct-nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('Home') }}</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('About Us') }}</a>
                <a href="{{ route('blogs.index') }}" class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">{{ __('Blogs') }}</a>
                <a href="{{ route('albums.index') }}" class="{{ request()->routeIs('albums.*') ? 'active' : '' }}">{{ __('Albums') }}</a>
                <a href="{{ route('testimonials.index') }}" class="{{ request()->routeIs('testimonials.*') ? 'active' : '' }}">{{ __('Testimonials') }}</a>
                <a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages.*') ? 'active' : '' }}">{{ __('Top Packages') }}</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">{{ __('Contact Us') }}</a>
            </nav>

            <div class="ct-header-actions">
                <div class="ct-lang-switch" role="group" aria-label="{{ __('Language') }}">
                    @foreach ($availableLocales as $code => $info)
                        <a href="{{ route('language.switch', $code) }}"
                           class="ct-lang-btn {{ $currentLocale === $code ? 'active' : '' }}"
                           lang="{{ $code }}"
                           title="{{ $info['label'] ?? $code }}">
                            {{ $info['short'] ?? strtoupper($code) }}
                        </a>
                    @endforeach
                </div>

                <button type="button" class="ct-nav-toggle" aria-expanded="false" aria-controls="site-nav" onclick="toggleNav(this)">
                    {{ __('Menu') }}
                </button>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="ct-footer">
        <div class="ct-container">
            <div class="ct-footer-grid">
                <div>
                    <img src="{{ \App\Support\Branding::footerLogoUrl() }}" alt="{{ $siteName }}" height="44" style="margin-bottom: 1.1rem;">
                    <p>{{ config('site.description') }}</p>
                    @include('partials.social-links')
                </div>
                <div>
                    <h4>{{ __('Explore') }}</h4>
                    <p><a href="{{ route('packages.index') }}">{{ __('Top Packages') }}</a></p>
                    <p><a href="{{ route('blogs.index') }}">{{ __('Blogs') }}</a></p>
                    <p><a href="{{ route('albums.index') }}">{{ __('Albums') }}</a></p>
                </div>
                <div>
                    <h4>{{ __('Company') }}</h4>
                    <p><a href="{{ route('about') }}">{{ __('About Us') }}</a></p>
                    <p><a href="{{ route('testimonials.index') }}">{{ __('Testimonials') }}</a></p>
                    <p><a href="{{ route('contact') }}">{{ __('Contact Us') }}</a></p>
                </div>
            </div>
            <div class="ct-footer-bottom">
                &copy; {{ date('Y') }} {{ $siteName }}. {{ __('All rights reserved.') }}
            </div>
        </div>
    </footer>

    <script>
        function toggleNav(btn) {
            const nav = document.getElementById('site-nav');
            const header = document.getElementById('site-header');
            const open = nav.classList.toggle('open');
            btn.setAttribute('aria-expanded', open);
            header.classList.toggle('is-open', open);
        }

        (function () {
            const header = document.getElementById('site-header');
            const onScroll = () => {
                header.classList.toggle('is-solid', window.scrollY > 24);
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });

            const reveals = document.querySelectorAll('.ct-reveal');
            if (!('IntersectionObserver' in window) || reveals.length === 0) {
                reveals.forEach((el) => el.classList.add('is-visible'));
                return;
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

            reveals.forEach((el) => observer.observe(el));
        })();
    </script>
    @stack('scripts')
</body>
</html>
