<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * "Continue with Google / Facebook" sign-in.
 *
 * These are browser-facing GET routes (registered in routes/web.php so the
 * session middleware Socialite needs is available). On success the provider
 * sends the browser here, we issue a Sanctum token and hand it to the SPA,
 * which stores it like any other login token.
 */
class SocialAuthController extends Controller
{
    /**
     * Providers this controller answers for; anything else is a 404.
     *
     * @var list<string>
     */
    private const PROVIDERS = ['google', 'facebook'];

    /**
     * Send the browser to the provider's consent screen.
     */
    public function redirect(string $provider): RedirectResponse
    {
        $this->assertSupported($provider);

        if (! $this->isConfigured($provider)) {
            return $this->fail('not_configured');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider's callback, then pass a token to the SPA.
     */
    public function callback(string $provider): RedirectResponse
    {
        $this->assertSupported($provider);

        if (! $this->isConfigured($provider)) {
            return $this->fail('not_configured');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Throwable $exception) {
            Log::warning('OAuth callback failed', [
                'provider' => $provider,
                'error' => $exception->getMessage(),
            ]);

            return $this->fail('authentication_failed');
        }

        $email = $socialUser->getEmail();

        if ($email === null || $email === '') {
            return $this->fail('email_not_shared');
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $email,
                'email' => $email,
                // OAuth accounts never enter a password. The "hashed" cast
                // hashes this random value; it cannot be used to sign in.
                'password' => Str::random(40),
                'avatar' => $socialUser->getAvatar(),
            ]);
            $user->assignRole('user');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return redirect()->away(
            rtrim((string) config('app.frontend_url'), '/').'/auth/callback?token='.urlencode($token)
        );
    }

    private function assertSupported(string $provider): void
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);
    }

    private function isConfigured(string $provider): bool
    {
        return filled((string) config("services.{$provider}.client_id"))
            && filled((string) config("services.{$provider}.client_secret"));
    }

    /**
     * Send the browser back to the SPA's login page with a reason code.
     */
    private function fail(string $reason): RedirectResponse
    {
        return redirect()->away(
            rtrim((string) config('app.frontend_url'), '/').'/login?error=oauth_'.$reason
        );
    }
}
