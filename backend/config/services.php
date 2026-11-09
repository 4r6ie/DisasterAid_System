<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third-party services
    |--------------------------------------------------------------------------
    |
    | Only the OAuth providers used for "continue with ..." sign-in are
    | configured here. Each is optional: when the credentials are empty the
    | API reports the provider as not configured instead of failing.
    |
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // Must match the redirect URI registered in the Google console.
        'redirect' => env('GOOGLE_REDIRECT_URI', '/oauth/google/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        // Must match the redirect URI registered in the Facebook app.
        'redirect' => env('FACEBOOK_REDIRECT_URI', '/oauth/facebook/callback'),
    ],

];
