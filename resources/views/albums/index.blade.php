@extends('layouts.app')

@section('content')
    <div class="ct-page-header">
        <div class="ct-container">
            <h1>{{ __('Photo Albums') }}</h1>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if ($albums->isEmpty())
                <p style="text-align: center; color: var(--text-muted);">{{ __('No albums published yet.') }}</p>
            @else
                <div class="ct-grid ct-grid-3">
                    @foreach ($albums as $album)
                        <article class="ct-card">
                            @php $cover = $album->getFirstMedia('cover') ?? $album->getFirstMedia('files'); @endphp
                            @if ($cover)
                                <img class="ct-card-img" src="{{ $cover->getUrl('thumb') }}" alt="{{ $album->name }}" loading="lazy">
                            @endif
                            <div class="ct-card-body">
                                <h2 class="ct-card-title"><a href="{{ route('albums.show', $album->slug) }}">{{ $album->name }}</a></h2>
                                <p class="ct-card-meta">{{ $album->created_at->format('M d, Y') }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="ct-pagination">{{ $albums->links() }}</div>
            @endif
        </div>
    </section>
@endsection
