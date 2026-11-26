@extends('layouts.app')

@section('content')
    <div class="ct-page-header ct-page-header-testimonials">
        <div class="ct-container">
            <p class="ct-eyebrow">{{ __('Guest stories') }}</p>
            <h1>{{ __('Testimonials') }}</h1>
            <p>{{ __('Real experiences from guests who explored Sri Lanka with us.') }}</p>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if ($testimonials->isEmpty())
                <p class="ct-center" style="color: var(--text-muted);">{{ __('No testimonials yet.') }}</p>
            @else
                <div class="ct-grid ct-grid-2">
                    @foreach ($testimonials as $testimonial)
                        <blockquote class="ct-testimonial ct-reveal">
                            <div class="ct-stars">{{ str_repeat('★', $testimonial->rating) }}</div>
                            <p>"{{ $testimonial->content }}"</p>
                            <p class="ct-testimonial-author">{{ $testimonial->author_name }}</p>
                            @if ($testimonial->author_country)
                                <p class="ct-testimonial-country">{{ $testimonial->author_country }}</p>
                            @endif
                        </blockquote>
                    @endforeach
                </div>
                <div class="ct-pagination">{{ $testimonials->links() }}</div>
            @endif
        </div>
    </section>
@endsection
