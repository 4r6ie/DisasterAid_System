<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * One account per role so the API and the UI can be exercised by hand.
     *
     * Skipped in production unless SEED_DEMO_USERS=true.
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
        if (app()->environment('production')
            && ! filter_var((string) env('SEED_DEMO_USERS'), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $password = (string) env('DEMO_PASSWORD', 'password');

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
}
