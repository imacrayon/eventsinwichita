<?php

return [
    'facebook' => [
        // Requires user permissions to access events from pages that have an
        // 18+ age restriction.
        'token' => env('FACEBOOK_TOKEN')
    ],
    'geocoder' => [
        'token' => env('GOOGLE_SECRET')
    ]
];
