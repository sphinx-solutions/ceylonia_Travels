<?php

/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        'defaults' => [
            'title' => env('SITE_NAME', 'Ceyloania Travels'),
            'titleBefore' => false,
            'description' => env(
                'SITE_DESCRIPTION',
                'Curated Sri Lankan tours for European travellers — cultural heritage, wildlife safaris, tea country, and tropical beaches with expert local guides.'
            ),
            'separator' => ' | ',
            'keywords' => [],
            'canonical' => false,
            'robots' => 'index, follow',
        ],
        'webmaster_tags' => [
            'google' => null,
            'bing' => null,
            'alexa' => null,
            'pinterest' => null,
            'yandex' => null,
            'norton' => null,
        ],
        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title' => env('SITE_NAME', 'Ceyloania Travels'),
            'description' => env(
                'SITE_DESCRIPTION',
                'Curated Sri Lankan tours for European travellers — cultural heritage, wildlife safaris, tea country, and tropical beaches with expert local guides.'
            ),
            'url' => false,
            'type' => 'website',
            'site_name' => env('SITE_NAME', 'Ceyloania Travels'),
            'images' => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
            'card' => 'summary_large_image',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title' => env('SITE_NAME', 'Ceyloania Travels'),
            'description' => env(
                'SITE_DESCRIPTION',
                'Curated Sri Lankan tours for European travellers — cultural heritage, wildlife safaris, tea country, and tropical beaches with expert local guides.'
            ),
            'url' => false,
            'type' => 'WebPage',
            'images' => [],
        ],
    ],
];
