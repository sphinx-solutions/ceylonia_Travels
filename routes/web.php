<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ShareSiteData;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middlewares\CacheResponse;
use Spatie\ResponseCache\Middlewares\DoNotCacheResponse;

Route::middleware([SetLocale::class, ShareSiteData::class, CacheResponse::class])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/about', [HomeController::class, 'about'])->name('about');
        Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('testimonials.index');

        Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
        Route::get('/blogs/{post:slug}', [BlogController::class, 'show'])->name('blogs.show');

        Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
        Route::get('/albums/{album:slug}', [AlbumController::class, 'show'])->name('albums.show');

        Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
        Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

        Route::get('/contact', [ContactController::class, 'index'])->name('contact');
        Route::post('/contact', [ContactController::class, 'store'])
            ->middleware(DoNotCacheResponse::class)
            ->name('contact.store');
    });

Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->whereIn('locale', array_keys(config('locales.available', ['en' => []])))
    ->middleware(DoNotCacheResponse::class)
    ->name('language.switch');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
