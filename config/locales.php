<?php

return [
    'default' => env('APP_LOCALE', 'en'),

    'available' => [
        'en' => [
            'label' => 'English',
            'native' => 'English',
            'short' => 'EN',
            'dir' => 'ltr',
        ],
        'it' => [
            'label' => 'Italian',
            'native' => 'Italiano',
            'short' => 'IT',
            'dir' => 'ltr',
        ],
    ],

    'session_key' => 'app_locale',
];
