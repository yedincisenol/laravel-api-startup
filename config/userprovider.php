<?php

return [
    'facebook' => [
        'controller'    => 'App\\Http\\Controllers\\RegisterController',
        'method'        => 'withFacebook',
    ],
    'google' => [
        'controller'    => 'App\\Http\\Controllers\\RegisterController',
        'method'        => 'withGoogle',
    ],
    'twitter' => [
        'controller'    => 'App\\Http\\Controllers\\RegisterController',
        'method'        => 'withTwitter',
    ],
    'linkedin' => [
        'controller'    => 'App\\Http\\Controllers\\RegisterController',
        'method'        => 'withLinkedin',
    ],
];
