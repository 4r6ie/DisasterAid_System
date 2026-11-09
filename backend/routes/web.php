<?php

use App\Http\Controllers\API\V1\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'DisasterAid System API',
        'data' => [
            'version' => '1.0.0',
            'documentation' => url('/api/documentation'),
        ],
    ]);
});

// The SPA renders the reset form; this route exists so that
// route('password.reset') — which Laravel's ResetPassword notification calls
// to build the emailed link — resolves instead of throwing
// "Route [password.reset] not defined". It is never actually served here
// (the web server hands /reset-password to the SPA); the redirect is a
// fallback for when the API is reached directly.
Route::get('/reset-password', function () {
    $query = http_build_query(array_filter([
        'token' => request()->query('token'),
        'email' => request()->query('email'),
    ]));

    return redirect()->away(
        rtrim((string) config('app.frontend_url'), '/').'/reset-password'.($query ? '?'.$query : '')
    );
})->name('password.reset');

/*
|--------------------------------------------------------------------------
| OAuth sign-in (Google / Facebook)
|--------------------------------------------------------------------------
|
| Browser-facing GET routes, so they live here (web middleware group) rather
| than in routes/api.php: Socialite verifies the OAuth state through the
| session. The nginx gateway forwards /oauth/ to this app.
|
*/

Route::prefix('oauth')->group(function () {
    Route::get('{provider}/redirect', [SocialAuthController::class, 'redirect']);
    Route::get('{provider}/callback', [SocialAuthController::class, 'callback']);
});
