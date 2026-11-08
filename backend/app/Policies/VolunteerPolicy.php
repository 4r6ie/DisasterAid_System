<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Volunteer;

class VolunteerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view volunteers');
    }

    public function view(User $user, Volunteer $volunteer): bool
    {
        return $user->can('view volunteers');
    }

    public function create(User $user): bool
    {
        return $user->can('create volunteers');
    }

    public function update(User $user, Volunteer $volunteer): bool
    {
        return $user->can('edit volunteers');
    }

    public function delete(User $user, Volunteer $volunteer): bool
    {
        return $user->can('delete volunteers');
    }

    public function updateStatus(User $user, Volunteer $volunteer): bool
    {
        return $user->can('update volunteer status');
    }
}