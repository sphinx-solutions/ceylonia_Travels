@php
    $shareUrl = $canonical ?? url()->current();
    $shareTitle = $shareTitle ?? ($siteName ?? config('site.name'));
    $encodedUrl = rawurlencode($shareUrl);
    $encodedTitle = rawurlencode($shareTitle);
@endphp

<div class="ct-share" style="display:flex;gap:0.75rem;flex-wrap:wrap;margin:1.5rem 0;">
    <a class="ct-btn ct-btn-outline" style="color:inherit;border-color:rgba(13,79,79,.25);"
       href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}"
       target="_blank" rel="noopener noreferrer">Facebook</a>
    <a class="ct-btn ct-btn-outline" style="color:inherit;border-color:rgba(13,79,79,.25);"
       href="https://wa.me/?text={{ $encodedTitle }}%20{{ $encodedUrl }}"
       target="_blank" rel="noopener noreferrer">WhatsApp</a>
    <a class="ct-btn ct-btn-outline" style="color:inherit;border-color:rgba(13,79,79,.25);"
       href="https://twitter.com/intent/tweet?url={{ $encodedUrl }}&text={{ $encodedTitle }}"
       target="_blank" rel="noopener noreferrer">X</a>
    <button type="button" class="ct-btn ct-btn-outline" style="color:inherit;border-color:rgba(13,79,79,.25);"
            onclick="navigator.clipboard.writeText('{{ $shareUrl }}')">{{ __('Copy link') }}</button>
</div>
