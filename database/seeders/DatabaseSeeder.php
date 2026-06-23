<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\SiteBranding;
use App\Models\Testimonial;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@ceyloania.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ],
        );

        SiteBranding::instance();

        foreach (['en', 'it'] as $locale) {
            Setting::query()->updateOrCreate(
                ['key' => 'site_name', 'locale' => $locale],
                ['value' => config('site.name')],
            );

            Setting::query()->updateOrCreate(
                ['key' => 'site_description', 'locale' => $locale],
                ['value' => config('site.description')],
            );
        }

        Page::query()->updateOrCreate(
            ['slug' => 'about', 'locale' => 'en'],
            [
                'title' => 'About Us',
                'content' => '<p>Ceyloania Travels is a specialist Sri Lanka tour operator serving European travellers. We combine local expertise with European service standards.</p>',
                'meta_title' => 'About Ceyloania Travels',
                'meta_description' => 'Learn about our Sri Lanka travel agency and our commitment to European travellers.',
            ],
        );

        Page::query()->updateOrCreate(
            ['slug' => 'about', 'locale' => 'it'],
            [
                'title' => 'Chi Siamo',
                'content' => '<p>Ceyloania Travels è un tour operator specializzato in Sri Lanka al servizio dei viaggiatori europei.</p>',
                'meta_title' => 'Chi Siamo — Ceyloania Travels',
                'meta_description' => 'Scopri la nostra agenzia di viaggi in Sri Lanka.',
            ],
        );

        Page::query()->updateOrCreate(
            ['slug' => 'contact', 'locale' => 'en'],
            [
                'title' => 'Contact Us',
                'content' => '<p>We would love to hear from you. Fill in the form below and our team will respond within 24 hours.</p>',
            ],
        );

        Page::query()->updateOrCreate(
            ['slug' => 'contact', 'locale' => 'it'],
            [
                'title' => 'Contattaci',
                'content' => '<p>Saremo felici di sentirti. Compila il modulo qui sotto e il nostro team risponderà entro 24 ore.</p>',
            ],
        );

        $admin = User::query()->where('email', 'admin@ceyloania.test')->first();

        Post::query()->updateOrCreate(
            ['slug' => 'welcome-to-sri-lanka'],
            [
                'user_id' => $admin?->id,
                'title' => 'Welcome to Sri Lanka: A European Traveller\'s Guide',
                'content' => '<p>Sri Lanka offers an extraordinary blend of ancient culture, lush landscapes, and warm hospitality — all within a compact island easy to explore in two weeks.</p><p>From Sigiriya\'s rock fortress to the tea trails of Nuwara Eliya, every region tells a different story.</p>',
                'author' => 'Ceyloania Team',
                'locale' => 'en',
                'status' => 'published',
                'published_at' => now(),
                'meta_title' => 'Welcome to Sri Lanka',
                'meta_description' => 'A guide for European travellers discovering Sri Lanka.',
                'is_featured' => true,
            ],
        );

        TourPackage::query()->updateOrCreate(
            ['slug' => 'cultural-triangle-express'],
            [
                'title' => 'Cultural Triangle Express',
                'excerpt' => 'Sigiriya, Dambulla, and Anuradhapura in 5 days with private guide.',
                'content' => '<p>Discover the heart of ancient Sri Lanka with this curated 5-day journey through the Cultural Triangle.</p>',
                'destination' => 'Cultural Triangle',
                'duration_days' => 5,
                'price_from' => 890,
                'currency' => 'EUR',
                'locale' => 'en',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        TourPackage::query()->updateOrCreate(
            ['slug' => 'triangolo-culturale'],
            [
                'title' => 'Triangolo Culturale Express',
                'excerpt' => 'Sigiriya, Dambulla e Anuradhapura in 5 giorni con guida privata.',
                'content' => '<p>Scopri il cuore dell\'antica Sri Lanka con questo viaggio di 5 giorni attraverso il Triangolo Culturale.</p>',
                'destination' => 'Triangolo Culturale',
                'duration_days' => 5,
                'price_from' => 890,
                'currency' => 'EUR',
                'locale' => 'it',
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        Testimonial::query()->updateOrCreate(
            ['author_name' => 'Marco Rossi', 'locale' => 'it'],
            [
                'author_country' => 'Italy',
                'content' => 'Un viaggio perfetto! La guida parlava italiano e ogni dettaglio era curato. Consigliatissimo.',
                'rating' => 5,
                'is_published' => true,
                'sort_order' => 1,
            ],
        );

        Testimonial::query()->updateOrCreate(
            ['author_name' => 'Emma Thompson', 'locale' => 'en'],
            [
                'author_country' => 'United Kingdom',
                'content' => 'From the safari in Yala to the beaches of Mirissa — every day was magical. Ceyloania made it effortless.',
                'rating' => 5,
                'is_published' => true,
                'sort_order' => 2,
            ],
        );

        Album::query()->updateOrCreate(
            ['slug' => 'sri-lanka-highlights'],
            [
                'name' => 'Sri Lanka Highlights',
                'description' => '<p>A visual journey across our favourite island moments.</p>',
                'locale' => 'en',
                'is_published' => true,
                'sort_order' => 1,
            ],
        );
    }
}
