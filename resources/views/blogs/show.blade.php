@extends('layouts.app')

@section('content')
    <article>
        @if ($post->getFirstMedia('banner'))
            <img src="{{ $post->getFirstMedia('banner')->getUrl('og') }}" alt="{{ $post->title }}" style="width:100%;max-height:420px;object-fit:cover;">
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
                                @if ($item->getFirstMedia('banner'))
                                    <img class="ct-card-img" src="{{ $item->getFirstMedia('banner')->getUrl('thumb') }}" alt="{{ $item->title }}" loading="lazy">
                                @endif
                                <div class="ct-card-body">
                                    <h3 class="ct-card-title"><a href="{{ route('blogs.show', $item->slug) }}">{{ $item->title }}</a></h3>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>
@endsection
