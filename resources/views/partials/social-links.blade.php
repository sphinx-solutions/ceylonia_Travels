@php
    $settings = $siteSettings ?? collect();
    $social = [
        'facebook' => $settings->where('key', 'facebook')->first()?->value ?? null,
        'instagram' => $settings->where('key', 'instagram')->first()?->value ?? null,
        'youtube' => $settings->where('key', 'youtube')->first()?->value ?? null,
        'tiktok' => $settings->where('key', 'tiktok')->first()?->value ?? null,
        'twitter' => $settings->where('key', 'twitter')->first()?->value ?? null,
    ];
    $social = array_filter($social);
@endphp

@if ($social !== [])
    <p class="ct-social" style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
        @foreach ($social as $network => $href)
            <a href="{{ $href }}" target="_blank" rel="noopener noreferrer">{{ ucfirst($network) }}</a>
        @endforeach
    </p>
@endif
