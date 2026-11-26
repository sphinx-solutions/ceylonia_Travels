@extends('layouts.app')

@section('content')
    @if ($package->getFirstMedia('cover'))
        <div class="ct-detail-banner">
            <img src="{{ $package->getFirstMedia('cover')->getUrl('og') }}" alt="{{ $package->title }}">
        </div>
    @else
        <div class="ct-page-header ct-page-header-packages">
            <div class="ct-container">
                <h1>{{ $package->title }}</h1>
            </div>
        </div>
    @endif

    <section class="ct-section">
        <div class="ct-container ct-prose">
            <h1>{{ $package->title }}</h1>
            <p class="ct-card-meta">
                @if ($package->duration_days){{ $package->duration_days }} {{ __('days') }}@endif
                @if ($package->destination) · {{ $package->destination }}@endif
            </p>
            @if ($package->price_from)
                <p class="ct-price" style="margin-bottom: 1.5rem;">{{ number_format($package->price_from, 0) }} {{ $package->currency }} <span>{{ __('from per person') }}</span></p>
            @endif
            @if ($package->excerpt)
                <p><strong>{{ $package->excerpt }}</strong></p>
            @endif
            {!! $package->content !!}
            @include('partials.share-buttons', ['canonical' => $canonical ?? route('packages.show', $package->slug), 'shareTitle' => $package->title])
            <p style="margin-top: 2rem;">
                <a href="{{ route('contact') }}" class="ct-btn ct-btn-primary">{{ __('Enquire About This Package') }}</a>
            </p>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="ct-section ct-section-alt">
            <div class="ct-container">
                <h2 class="ct-section-title">{{ __('You May Also Like') }}</h2>
                <div class="ct-grid ct-grid-3">
                    @foreach ($related as $item)
                        <article class="ct-card">
                            <div class="ct-card-media">
                                @if ($item->getFirstMedia('cover'))
                                    <img class="ct-card-img" src="{{ $item->getFirstMedia('cover')->getUrl('thumb') }}" alt="{{ $item->title }}" loading="lazy">
                                @else
                                    <img class="ct-card-img" src="{{ asset('images/hero-packages.jpg') }}" alt="{{ $item->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('packages.show', $item->slug) }}">{{ $item->title }}</a></h3>
                                <a class="ct-link-more" href="{{ route('packages.show', $item->slug) }}">{{ __('Details') }}</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
