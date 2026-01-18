<?php

return [

    'layout' => [
        'theme' => 'default',
        'labels_position' => 'inset', // above | inside | inset (overlaps border) | floating (goes from inside to above on focus) | hidden
        'mode_toggle' => true
    ],

    'features' => [
        'register' => true,
        'password_strength' => true,
        'password_confirm' => true,
        'password_reveal' => true,
        'captcha' => false,
        'autocomplete_email' => true,
        'login_remember_me' => true,
    ],

    'social' => [
        'google' => true,
        'apple' => false,
    ],

    'captcha' => [
        'provider' => 'cloudflare_turnstile',
        'site_key' => env('CAPTCHA_SITE_KEY'),
    ],

];
