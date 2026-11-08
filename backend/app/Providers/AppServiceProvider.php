<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Abilities that are not tied to an Eloquent model.
        Gate::define('viewDashboard', fn (User $user) => $user->can('view dashboard'));
        Gate::define('viewAuditLogs', fn (User $user) => $user->can('view audit logs'));
    }
}
