@extends('layouts.app')

@section('content')
    <article>
        @if ($post->getFirstMedia('banner'))
            <div class="ct-detail-banner">
                <img src="{{ $post->getFirstMedia('banner')->getUrl('og') }}" alt="{{ $post->title }}">
            </div>
        @else
            <div class="ct-page-header ct-page-header-blogs">
                <div class="ct-container">
                    <h1>{{ $post->title }}</h1>
                </div>
            </div>
        @endif

        <section class="ct-section">
            <div class="ct-container ct-prose">
                <h1>{{ $post->title }}</h1>
                <p class="ct-card-meta">{{ $post->author }} · {{ $post->published_at?->format('F j, Y') }}</p>
                @include('partials.share-buttons', ['canonical' => $canonical ?? route('blogs.show', $post->slug), 'shareTitle' => $post->title])
                {!! $post->content !!}
            </div>
        </section>

        @if ($related->isNotEmpty())
            <section class="ct-section ct-section-alt">
                <div class="ct-container">
                    <h2 class="ct-section-title">{{ __('Related Articles') }}</h2>
                    <div class="ct-grid ct-grid-3">
                        @foreach ($related as $item)
                            <article class="ct-card">
                                <div class="ct-card-media">
                                    @if ($item->getFirstMedia('banner'))
                                        <img class="ct-card-img" src="{{ $item->getFirstMedia('banner')->getUrl('thumb') }}" alt="{{ $item->title }}" loading="lazy">
                                    @else
                                        <img class="ct-card-img" src="{{ asset('images/hero-tea.jpg') }}" alt="{{ $item->title }}" loading="lazy">
                                    @endif
                                </div>
                                <div class="ct-card-body">
                                    <h3 class="ct-card-title"><a href="{{ route('blogs.show', $item->slug) }}">{{ $item->title }}</a></h3>
                                    <a class="ct-link-more" href="{{ route('blogs.show', $item->slug) }}">{{ __('Read more') }}</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
@endsection
