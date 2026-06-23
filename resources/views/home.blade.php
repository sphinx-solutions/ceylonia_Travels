@extends('layouts.app')

@section('content')
    <section class="ct-hero">
        <div class="ct-container ct-hero-content">
            <h1>{{ __('Discover') }} <span class="ct-hero-accent">{{ __('Sri Lanka') }}</span></h1>
            <p>{{ __('Curated journeys for European travellers — ancient temples, tea hills, wildlife safaris, and golden beaches with expert local guides.') }}</p>
            <div>
                <a href="{{ route('packages.index') }}" class="ct-btn ct-btn-primary">{{ __('View Top Packages') }}</a>
                <a href="{{ route('contact') }}" class="ct-btn ct-btn-outline">{{ __('Plan Your Trip') }}</a>
            </div>
        </div>
    </section>

    @if ($featuredPackages->isNotEmpty())
        <section class="ct-section ct-section-alt">
            <div class="ct-container">
                <h2 class="ct-section-title">{{ __('Top Packages') }}</h2>
                <p class="ct-section-subtitle">{{ __('Handpicked Sri Lanka tours designed for comfort, culture, and unforgettable experiences.') }}</p>
                <div class="ct-grid ct-grid-3">
                    @foreach ($featuredPackages as $package)
                        <article class="ct-card">
                            @if ($package->getFirstMedia('cover'))
                                <img class="ct-card-img" src="{{ $package->getFirstMedia('cover')->getUrl('thumb') }}" alt="{{ $package->title }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('packages.show', $package->slug) }}">{{ $package->title }}</a></h3>
                                <p class="ct-card-meta">
                                    @if ($package->duration_days){{ $package->duration_days }} {{ __('days') }}@endif
                                    @if ($package->destination) · {{ $package->destination }}@endif
                                </p>
                                <p class="ct-card-text">{{ $package->excerpt }}</p>
                                @if ($package->price_from)
                                    <p class="ct-price">{{ number_format($package->price_from, 0) }} {{ $package->currency }} <span>{{ __('from') }}</span></p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('packages.index') }}" class="ct-btn ct-btn-primary">{{ __('See All Packages') }}</a>
                </p>
            </div>
        </section>
    @endif

    @if ($testimonials->isNotEmpty())
        <section class="ct-section">
            <div class="ct-container">
                <h2 class="ct-section-title">{{ __('What Travellers Say') }}</h2>
                <p class="ct-section-subtitle">{{ __('Real experiences from guests who explored Sri Lanka with us.') }}</p>
                <div class="ct-grid ct-grid-2">
                    @foreach ($testimonials as $testimonial)
                        <blockquote class="ct-testimonial">
                            <div class="ct-stars">{{ str_repeat('★', $testimonial->rating) }}</div>
                            <p>"{{ $testimonial->content }}"</p>
                            <p class="ct-testimonial-author">{{ $testimonial->author_name }}</p>
                            @if ($testimonial->author_country)
                                <p class="ct-testimonial-country">{{ $testimonial->author_country }}</p>
                            @endif
                        </blockquote>
                    @endforeach
                </div>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('testimonials.index') }}" class="ct-btn ct-btn-primary">{{ __('Read More Testimonials') }}</a>
                </p>
            </div>
        </section>
    @endif

    @if ($latestPosts->isNotEmpty())
        <section class="ct-section ct-section-alt">
            <div class="ct-container">
                <h2 class="ct-section-title">{{ __('Travel Blog') }}</h2>
                <p class="ct-section-subtitle">{{ __('Stories, tips, and inspiration for your Sri Lanka adventure.') }}</p>
                <div class="ct-grid ct-grid-3">
                    @foreach ($latestPosts as $post)
                        <article class="ct-card">
                            @if ($post->getFirstMedia('banner'))
                                <img class="ct-card-img" src="{{ $post->getFirstMedia('banner')->getUrl('thumb') }}" alt="{{ $post->title }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                <p class="ct-card-meta">{{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</p>
                                <p class="ct-card-text">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('blogs.index') }}" class="ct-btn ct-btn-primary">{{ __('View All Blogs') }}</a>
                </p>
            </div>
        </section>
    @endif

    @if ($albums->isNotEmpty())
        <section class="ct-section">
            <div class="ct-container">
                <h2 class="ct-section-title">{{ __('Photo Albums') }}</h2>
                <p class="ct-section-subtitle">{{ __('Moments captured across the island — from wildlife to coastlines.') }}</p>
                <div class="ct-grid ct-grid-4">
                    @foreach ($albums as $album)
                        <article class="ct-card">
                            @php $cover = $album->getFirstMedia('cover') ?? $album->getFirstMedia('files'); @endphp
                            @if ($cover)
                                <img class="ct-card-img" src="{{ $cover->getUrl('thumb') }}" alt="{{ $album->name }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('albums.show', $album->slug) }}">{{ $album->name }}</a></h3>
                            </div>
                        </article>
                    @endforeach
                </div>
                <p style="text-align: center; margin-top: 2rem;">
                    <a href="{{ route('albums.index') }}" class="ct-btn ct-btn-primary">{{ __('Browse Albums') }}</a>
                </p>
            </div>
        </section>
    @endif

    <section class="ct-section ct-section-alt">
        <div class="ct-container" style="text-align: center;">
            <h2 class="ct-section-title">{{ __('Ready to Explore Sri Lanka?') }}</h2>
            <p class="ct-section-subtitle">{{ __('Tell us your travel dates and interests — we will craft a personalised itinerary within 24 hours.') }}</p>
            <a href="{{ route('contact') }}" class="ct-btn ct-btn-primary">{{ __('Get in Touch') }}</a>
        </div>
    </section>
@endsection
