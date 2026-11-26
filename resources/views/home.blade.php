@extends('layouts.app')

@section('content')
    <section class="ct-hero">
        <div class="ct-hero-media" aria-hidden="true">
            <img src="{{ asset('images/hero-home.jpg') }}" alt="" width="2000" height="1333">
        </div>
        <div class="ct-hero-overlay" aria-hidden="true"></div>
        <div class="ct-container ct-hero-content">
            <p class="ct-eyebrow">{{ $siteName ?? config('site.name') }}</p>
            <h1>{{ __('Discover') }} <span class="ct-hero-accent">{{ __('Sri Lanka') }}</span></h1>
            <p class="ct-hero-lead">{{ __('Curated journeys for European travellers — ancient temples, tea hills, wildlife safaris, and golden beaches with expert local guides.') }}</p>
            <div class="ct-btn-row">
                <a href="{{ route('packages.index') }}" class="ct-btn ct-btn-primary">{{ __('View Top Packages') }}</a>
                <a href="{{ route('contact') }}" class="ct-btn ct-btn-outline">{{ __('Plan Your Trip') }}</a>
            </div>
            <div class="ct-hero-meta">
                <div class="ct-hero-meta-item">
                    <strong>EN / IT</strong>
                    <span>{{ __('Guided journeys') }}</span>
                </div>
                <div class="ct-hero-meta-item">
                    <strong>24h</strong>
                    <span>{{ __('Itinerary reply') }}</span>
                </div>
                <div class="ct-hero-meta-item">
                    <strong>Private</strong>
                    <span>{{ __('Tailor-made tours') }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="ct-section" style="padding-top: 0; padding-bottom: 1rem;">
        <div class="ct-container-wide">
            <div class="ct-experiences">
                <article class="ct-experience ct-reveal">
                    <img src="{{ asset('images/hero-culture.jpg') }}" alt="{{ __('Cultural Triangle') }}" loading="lazy">
                    <div class="ct-experience-body">
                        <h3>{{ __('Culture') }}</h3>
                        <p>{{ __('Temples, fortresses & living heritage') }}</p>
                    </div>
                </article>
                <article class="ct-experience ct-reveal ct-reveal-delay-1">
                    <img src="{{ asset('images/hero-tea.jpg') }}" alt="{{ __('Hill country tea estates') }}" loading="lazy">
                    <div class="ct-experience-body">
                        <h3>{{ __('Hill Country') }}</h3>
                        <p>{{ __('Tea trails, mist & mountain air') }}</p>
                    </div>
                </article>
                <article class="ct-experience ct-reveal ct-reveal-delay-2">
                    <img src="{{ asset('images/hero-wildlife.jpg') }}" alt="{{ __('Wildlife safaris') }}" loading="lazy">
                    <div class="ct-experience-body">
                        <h3>{{ __('Wildlife') }}</h3>
                        <p>{{ __('Safaris, elephants & national parks') }}</p>
                    </div>
                </article>
                <article class="ct-experience ct-reveal ct-reveal-delay-3">
                    <img src="{{ asset('images/hero-beach.jpg') }}" alt="{{ __('Southern beaches') }}" loading="lazy">
                    <div class="ct-experience-body">
                        <h3>{{ __('Coast') }}</h3>
                        <p>{{ __('Golden shores & Indian Ocean calm') }}</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredPackages->isNotEmpty())
        <section class="ct-section ct-section-alt">
            <div class="ct-container">
                <div class="ct-section-head ct-reveal">
                    <div>
                        <p class="ct-eyebrow" style="color: var(--emerald);">{{ __('Journeys') }}</p>
                        <h2 class="ct-section-title">{{ __('Top Packages') }}</h2>
                        <p class="ct-section-subtitle">{{ __('Handpicked Sri Lanka tours designed for comfort, culture, and unforgettable experiences.') }}</p>
                    </div>
                    <a href="{{ route('packages.index') }}" class="ct-btn ct-btn-dark">{{ __('See All Packages') }}</a>
                </div>
                <div class="ct-grid ct-grid-3">
                    @foreach ($featuredPackages as $package)
                        <article class="ct-card ct-reveal">
                            <div class="ct-card-media">
                                @if ($package->duration_days)
                                    <span class="ct-card-badge">{{ $package->duration_days }} {{ __('days') }}</span>
                                @endif
                                @if ($package->getFirstMedia('cover'))
                                    <img class="ct-card-img" src="{{ $package->getFirstMedia('cover')->getUrl('thumb') }}" alt="{{ $package->title }}" loading="lazy">
                                @else
                                    <img class="ct-card-img" src="{{ asset('images/hero-packages.jpg') }}" alt="{{ $package->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('packages.show', $package->slug) }}">{{ $package->title }}</a></h3>
                                <p class="ct-card-meta">
                                    @if ($package->destination){{ $package->destination }}@endif
                                </p>
                                <p class="ct-card-text">{{ $package->excerpt }}</p>
                                <div class="ct-card-footer">
                                    @if ($package->price_from)
                                        <p class="ct-price">{{ number_format($package->price_from, 0) }} {{ $package->currency }} <span>{{ __('from') }}</span></p>
                                    @else
                                        <span></span>
                                    @endif
                                    <a class="ct-link-more" href="{{ route('packages.show', $package->slug) }}">{{ __('Details') }}</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($testimonials->isNotEmpty())
        <section class="ct-section">
            <div class="ct-container">
                <div class="ct-reveal">
                    <p class="ct-eyebrow" style="justify-content: center; color: var(--emerald);">{{ __('Guest stories') }}</p>
                    <h2 class="ct-section-title">{{ __('What Travellers Say') }}</h2>
                    <p class="ct-section-subtitle">{{ __('Real experiences from guests who explored Sri Lanka with us.') }}</p>
                </div>
                <div class="ct-grid ct-grid-2">
                    @foreach ($testimonials as $testimonial)
                        <blockquote class="ct-testimonial ct-reveal">
                            <div class="ct-stars">{{ str_repeat('★', $testimonial->rating) }}</div>
                            <p>"{{ $testimonial->content }}"</p>
                            <p class="ct-testimonial-author">{{ $testimonial->author_name }}</p>
                            @if ($testimonial->author_country)
                                <p class="ct-testimonial-country">{{ $testimonial->author_country }}</p>
                            @endif
                        </blockquote>
                    @endforeach
                </div>
                <p class="ct-center" style="margin-top: 2.25rem;">
                    <a href="{{ route('testimonials.index') }}" class="ct-btn ct-btn-dark">{{ __('Read More Testimonials') }}</a>
                </p>
            </div>
        </section>
    @endif

    @if ($latestPosts->isNotEmpty())
        <section class="ct-section ct-section-alt">
            <div class="ct-container">
                <div class="ct-section-head ct-reveal">
                    <div>
                        <p class="ct-eyebrow" style="color: var(--emerald);">{{ __('Inspiration') }}</p>
                        <h2 class="ct-section-title">{{ __('Travel Blog') }}</h2>
                        <p class="ct-section-subtitle">{{ __('Stories, tips, and inspiration for your Sri Lanka adventure.') }}</p>
                    </div>
                    <a href="{{ route('blogs.index') }}" class="ct-btn ct-btn-dark">{{ __('View All Blogs') }}</a>
                </div>
                <div class="ct-grid ct-grid-3">
                    @foreach ($latestPosts as $post)
                        <article class="ct-card ct-reveal">
                            <div class="ct-card-media">
                                @if ($post->getFirstMedia('banner'))
                                    <img class="ct-card-img" src="{{ $post->getFirstMedia('banner')->getUrl('thumb') }}" alt="{{ $post->title }}" loading="lazy">
                                @else
                                    <img class="ct-card-img" src="{{ asset('images/hero-tea.jpg') }}" alt="{{ $post->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                <p class="ct-card-meta">{{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</p>
                                <p class="ct-card-text">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                                <a class="ct-link-more" href="{{ route('blogs.show', $post->slug) }}">{{ __('Read more') }}</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($albums->isNotEmpty())
        <section class="ct-section">
            <div class="ct-container">
                <div class="ct-section-head ct-reveal">
                    <div>
                        <p class="ct-eyebrow" style="color: var(--emerald);">{{ __('Gallery') }}</p>
                        <h2 class="ct-section-title">{{ __('Photo Albums') }}</h2>
                        <p class="ct-section-subtitle">{{ __('Moments captured across the island — from wildlife to coastlines.') }}</p>
                    </div>
                    <a href="{{ route('albums.index') }}" class="ct-btn ct-btn-dark">{{ __('Browse Albums') }}</a>
                </div>
                <div class="ct-grid ct-grid-4">
                    @foreach ($albums as $album)
                        <article class="ct-card ct-card-mosaic ct-reveal">
                            <div class="ct-card-media">
                                @php $cover = $album->getFirstMedia('cover') ?? $album->getFirstMedia('files'); @endphp
                                @if ($cover)
                                    <img class="ct-card-img" src="{{ $cover->getUrl('thumb') }}" alt="{{ $album->name }}" loading="lazy">
                                @else
                                    <img class="ct-card-img" src="{{ asset('images/hero-wildlife.jpg') }}" alt="{{ $album->name }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('albums.show', $album->slug) }}">{{ $album->name }}</a></h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="ct-cta">
        <div class="ct-container ct-reveal">
            <h2>{{ __('Ready to Explore Sri Lanka?') }}</h2>
            <p>{{ __('Tell us your travel dates and interests — we will craft a personalised itinerary within 24 hours.') }}</p>
            <a href="{{ route('contact') }}" class="ct-btn ct-btn-primary">{{ __('Get in Touch') }}</a>
        </div>
    </section>
@endsection
