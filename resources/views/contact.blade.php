@extends('layouts.app')

@section('content')
    <div class="ct-page-header ct-page-header-contact">
        <div class="ct-container">
            <p class="ct-eyebrow">{{ __('Plan with us') }}</p>
            <h1>{{ __('Contact Us') }}</h1>
            <p>{{ __('Tell us your dates and interests — we reply within 24 hours.') }}</p>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container">
            @if (session('success'))
                <div class="ct-alert-success">{{ session('success') }}</div>
            @endif

            @if ($page?->content)
                <div class="ct-prose" style="margin-bottom: 2rem; text-align: center;">{!! $page->content !!}</div>
            @endif

            <form class="ct-form" method="POST" action="{{ route('contact.store') }}">
                @csrf
                <div class="ct-form-group">
                    <label for="name">{{ __('Name') }} *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')<span class="ct-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="ct-form-group">
                    <label for="email">{{ __('Email') }} *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<span class="ct-field-error">{{ $message }}</span>@enderror
                </div>
                <div class="ct-form-group">
                    <label for="phone">{{ __('Phone') }}</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="ct-form-group">
                    <label for="subject">{{ __('Subject') }}</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}">
                </div>
                <div class="ct-form-group">
                    <label for="message">{{ __('Message') }} *</label>
                    <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                    @error('message')<span class="ct-field-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="ct-btn ct-btn-primary">{{ __('Send Message') }}</button>
            </form>
        </div>
    </section>
@endsection
