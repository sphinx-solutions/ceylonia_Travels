@extends('layouts.app')

@section('content')
    <div class="ct-page-header">
        <div class="ct-container">
            <h1>{{ __('Blogs') }}</h1>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if ($posts->isEmpty())
                <p style="text-align: center; color: var(--text-muted);">{{ __('No blog posts published yet. Check back soon.') }}</p>
            @else
                <div class="ct-grid ct-grid-3">
                    @foreach ($posts as $post)
                        <article class="ct-card">
                            @if ($post->getFirstMedia('banner'))
                                <img class="ct-card-img" src="{{ $post->getFirstMedia('banner')->getUrl('thumb') }}" alt="{{ $post->title }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h2 class="ct-card-title"><a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a></h2>
                                <p class="ct-card-meta">{{ $post->author }} · {{ $post->published_at?->format('M d, Y') }}</p>
                                <p class="ct-card-text">{{ Str::limit(strip_tags($post->content), 140) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="ct-pagination">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
@endsection
