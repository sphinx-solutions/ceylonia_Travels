@extends('layouts.app')

@section('content')
    @if ($package->getFirstMedia('cover'))
        <img src="{{ $package->getFirstMedia('cover')->getUrl('og') }}" alt="{{ $package->title }}" style="width:100%;max-height:420px;object-fit:cover;">
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
                            @if ($item->getFirstMedia('cover'))
                                <img class="ct-card-img" src="{{ $item->getFirstMedia('cover')->getUrl('thumb') }}" alt="{{ $item->title }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h3 class="ct-card-title"><a href="{{ route('packages.show', $item->slug) }}">{{ $item->title }}</a></h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
