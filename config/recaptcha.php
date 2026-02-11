<?php

return [

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google reCAPTCHA v3 integration.
    | Get your keys from: https://www.google.com/recaptcha/admin
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'enabled' => env('RECAPTCHA_ENABLED', true),
    'threshold' => env('RECAPTCHA_THRESHOLD', 0.5), // Minimum score (0.0 to 1.0)

];
