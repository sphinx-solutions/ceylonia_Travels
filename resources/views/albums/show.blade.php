@extends('layouts.app')

@section('content')
    <div class="ct-page-header">
        <div class="ct-container">
            <h1>{{ $album->name }}</h1>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if ($album->description)
                <div class="ct-prose" style="margin-bottom: 2rem;">{!! $album->description !!}</div>
            @endif
            @include('partials.share-buttons', ['canonical' => $canonical ?? route('albums.show', $album->slug), 'shareTitle' => $album->name])

            @php $files = $album->getMedia('files'); @endphp
            @if ($files->isEmpty())
                <p style="text-align: center; color: var(--text-muted);">{{ __('No photos in this album yet.') }}</p>
            @else
                <div class="ct-gallery">
                    @foreach ($files as $media)
                        <img src="{{ $media->getUrl('thumb') }}" alt="{{ $media->name }}" loading="lazy"
                             onclick="window.open('{{ $media->getUrl() }}', '_blank')">
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
