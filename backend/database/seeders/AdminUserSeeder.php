<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@disasteraid.test');
        $password = env('ADMIN_PASSWORD');

        // A deployed instance must never fall back to a published default.
        if (! is_string($password) || $password === '') {
            if (app()->environment('production')) {
                throw new RuntimeException('ADMIN_PASSWORD must be set in production.');
            }

            $password = 'password';
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'System Administrator',
                // The model's "hashed" cast takes care of hashing.
                'password' => $password,
            ]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
