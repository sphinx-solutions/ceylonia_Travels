@extends('layouts.app')

@section('content')
    <div class="ct-page-header">
        <div class="ct-container">
            <h1>{{ __('Top Packages') }}</h1>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if ($packages->isEmpty())
                <p style="text-align: center; color: var(--text-muted);">{{ __('Packages coming soon. Contact us for a custom itinerary.') }}</p>
            @else
                <div class="ct-grid ct-grid-3">
                    @foreach ($packages as $package)
                        <article class="ct-card">
                            @if ($package->getFirstMedia('cover'))
                                <img class="ct-card-img" src="{{ $package->getFirstMedia('cover')->getUrl('thumb') }}" alt="{{ $package->title }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h2 class="ct-card-title"><a href="{{ route('packages.show', $package->slug) }}">{{ $package->title }}</a></h2>
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
                <div class="ct-pagination">{{ $packages->links() }}</div>
            @endif
        </div>
    </section>
@endsection
