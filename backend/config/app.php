<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'DisasterAid System'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    // Where the SPA is served from. Used for links the API hands to the
    // browser (password reset, OAuth callbacks) so they land on the SPA
    // rather than on an API route.
    'frontend_url' => env('FRONTEND_URL', env('APP_URL', 'http://localhost')),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => 'file',
        'store' => env('MAINTENANCE_STORE'),
    ],
    'providers' => ServiceProvider::defaultProviders()->merge([
        // Package Service Providers...
    ])->merge([
        // Application Service Providers...
        // App\Providers\AppServiceProvider::class,
    ])->merge([
        // Added Service Providers (Do not remove this line)...
    ])->toArray(),
    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),
];