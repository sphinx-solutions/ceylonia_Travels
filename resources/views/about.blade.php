@extends('layouts.app')

@section('content')
    <div class="ct-page-header ct-page-header-about">
        <div class="ct-container">
            <p class="ct-eyebrow">{{ __('Our story') }}</p>
            <h1>{{ $page?->title ?? __('About Us') }}</h1>
            <p>{{ __('Local expertise with European service standards across Sri Lanka.') }}</p>
        </div>
    </div>

    <section class="ct-section">
        <div class="ct-container ct-prose">
            @if ($page?->content)
                {!! $page->content !!}
            @else
                <p>{{ __('Ceyloania Travels is a specialist Sri Lanka tour operator serving European travellers. We combine local expertise with European service standards — comfortable transport, curated accommodations, and English- and Italian-speaking guides.') }}</p>
                <p>{{ __('From the Cultural Triangle and hill-country tea estates to Yala safaris and the southern coast, we design journeys that balance adventure with relaxation.') }}</p>
            @endif
        </div>
    </section>
@endsection
