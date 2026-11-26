@php
    $shareUrl = $canonical ?? url()->current();
    $shareTitle = $shareTitle ?? ($siteName ?? config('site.name'));
    $encodedUrl = rawurlencode($shareUrl);
    $encodedTitle = rawurlencode($shareTitle);
@endphp

<div class="ct-share">
    <a class="ct-btn ct-btn-outline"
       href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
       target="_blank" rel="noopener noreferrer">Facebook</a>
    <a class="ct-btn ct-btn-outline"
       href="https://wa.me/?text={{ $encodedTitle }}%20{{ $encodedUrl }}"
       target="_blank" rel="noopener noreferrer">WhatsApp</a>
    <a class="ct-btn ct-btn-outline"
       href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
       target="_blank" rel="noopener noreferrer">X</a>
    <button type="button" class="ct-btn ct-btn-outline"
            onclick="navigator.clipboard.writeText('{{ $shareUrl }}')">{{ __('Copy link') }}</button>
</div>
