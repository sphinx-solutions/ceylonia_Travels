<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AppliesSeo;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\ResponseCache\Facades\ResponseCache;

class ContactController extends Controller
{
    use AppliesSeo;

    public function index(): View
    {
        $locale = app()->getLocale();
        $page = Page::query()->where('slug', 'contact')->forLocale($locale)->first()
            ?? Page::query()->where('slug', 'contact')->forLocale('en')->first();

        $this->applySeo(fn ($seo) => $seo->setContactSeo());

        return view('contact', compact('page'));
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::query()->create($request->validated());

        ResponseCache::clear();

        return redirect()
            ->route('contact')
            ->with('success', __('Thank you! We will get back to you within 24 hours.'));
    }
}
