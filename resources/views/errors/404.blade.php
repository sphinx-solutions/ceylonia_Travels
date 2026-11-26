@extends('layouts.app')

@php
    try {
        \App\Services\SetSeoService::make()->setNoIndex();
        \Artesaos\SEOTools\Facades\SEOMeta::setTitle(__('Page not found').' | '.config('site.name'), false);
        \Artesaos\SEOTools\Facades\SEOMeta::setDescription(__('The page you are looking for could not be found.'));
    } catch (\Throwable $e) {
        //
    }
@endphp

@section('content')
    <div class="ct-page-header">
        <div class="ct-container">
            <h1>{{ __('Page not found') }}</h1>
        </div>
    </div>
    <section class="ct-section">
        <div class="ct-container" style="text-align:center;">
            <p>{{ __('The page you are looking for could not be found.') }}</p>
            <p style="margin-top:1.5rem;"><a href="{{ route('home') }}" class="ct-btn ct-btn-primary">{{ __('Home') }}</a></p>
        </div>
    </section>
@endsection
