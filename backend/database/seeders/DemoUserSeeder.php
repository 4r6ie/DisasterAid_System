<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DemoUserSeeder extends Seeder
{
    /**
     * One account per role so the API and the UI can be exercised by hand.
     *
     * These accounts are for local testing only. They are created when
     * SEED_DEMO_USERS=true, never in production, and only after DEMO_PASSWORD
     * has been set explicitly — there is deliberately no default password.
     */
    protected array $accounts = [
        'admin' => [
            'name' => 'Demo Admin',
            'email' => 'officer@disasteraid.test',
        ],
        'responder' => [
            'name' => 'Demo Responder',
            'email' => 'responder@disasteraid.test',
        ],
        'volunteer' => [
            'name' => 'Demo Volunteer',
            'email' => 'volunteer@disasteraid.test',
        ],
        'user' => [
            'name' => 'Demo Resident',
            'email' => 'resident@disasteraid.test',
        ],
    ];

    public function run(): void
    {
        if (! $this->enabled()) {
            return;
        }

        $password = env('DEMO_PASSWORD');

        if (! is_string($password) || $password === '') {
            throw new RuntimeException(
                'DEMO_PASSWORD must be set when SEED_DEMO_USERS=true.'
            );
        }

        foreach ($this->accounts as $role => $details) {
            $user = User::updateOrCreate(
                ['email' => $details['email']],
                [
                    'name' => $details['name'],
                    // The model's "hashed" cast takes care of hashing.
                    'password' => $password,
                ]
            );

            if (! $user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }

    protected function enabled(): bool
    {
        if (app()->environment('production')) {
            return false;
        }

        return filter_var((string) env('SEED_DEMO_USERS'), FILTER_VALIDATE_BOOLEAN);
    }
}
