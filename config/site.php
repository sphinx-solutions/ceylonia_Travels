<?php

return [
    'name' => env('SITE_NAME', 'Ceyloania Travels'),
    'tagline' => env('SITE_TAGLINE', 'Discover the Pearl of the Indian Ocean'),
    'description' => env(
        'SITE_DESCRIPTION',
        'Curated Sri Lankan tours for European travellers — cultural heritage, wildlife safaris, tea country, and tropical beaches with expert local guides.'
    ),
    'logo' => env('SITE_LOGO', 'images/logo-placeholder.svg'),
    'og_image' => env('SITE_OG_IMAGE', 'images/og-default.jpg'),
    'og_width' => (int) env('SITE_OG_WIDTH', 1200),
    'og_height' => (int) env('SITE_OG_HEIGHT', 630),
    'contact_email' => env('SITE_CONTACT_EMAIL', 'hello@ceyloania.com'),
    'publisher_name' => env('SITE_PUBLISHER_NAME', env('SITE_NAME', 'Ceyloania Travels')),
    'region' => env('SITE_REGION', 'Sri Lanka'),
    'available_languages' => ['en', 'it'],
];
