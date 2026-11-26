@extends('layouts.app')

@section('content')
    <div class="ct-page-header ct-page-header-packages">
        <div class="ct-container">
            <p class="ct-eyebrow">{{ __('Journeys') }}</p>
            <h1>{{ __('Top Packages') }}</h1>
            <p>{{ __('Handpicked Sri Lanka tours designed for comfort, culture, and unforgettable experiences.') }}</p>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if ($packages->isEmpty())
                <p class="ct-center" style="color: var(--text-muted);">{{ __('Packages coming soon. Contact us for a custom itinerary.') }}</p>
            @else
                <div class="ct-grid ct-grid-3">
                    @foreach ($packages as $package)
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
                                <h2 class="ct-card-title"><a href="{{ route('packages.show', $package->slug) }}">{{ $package->title }}</a></h2>
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
                <div class="ct-pagination">{{ $packages->links() }}</div>
            @endif
        </div>
    </section>
@endsection
