<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Setting;
use App\Services\SetSeoService;
use App\Support\Branding;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\ResponseCache\Facades\ResponseCache;

class ContactController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();
        $page = Page::query()->where('slug', 'contact')->forLocale($locale)->first()
            ?? Page::query()->where('slug', 'contact')->forLocale('en')->first();

        $this->seo()->setContactSeo();

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

    protected function seo(): SetSeoService
    {
        return new SetSeoService(
            Setting::query()->whereIn('locale', [app()->getLocale(), 'en'])->get(),
            Branding::logoUrl('og'),
        );
    }
}
