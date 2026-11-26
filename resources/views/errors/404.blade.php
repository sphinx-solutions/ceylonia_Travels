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
    <div class="ct-page-header ct-page-header-about">
        <div class="ct-container">
            <p class="ct-eyebrow">404</p>
            <h1>{{ __('Page not found') }}</h1>
            <p>{{ __('The page you are looking for could not be found.') }}</p>
        </div>
    </div>
    <section class="ct-section">
        <div class="ct-container ct-center">
            <a href="{{ route('home') }}" class="ct-btn ct-btn-primary">{{ __('Home') }}</a>
        </div>
    </section>
@endsection
