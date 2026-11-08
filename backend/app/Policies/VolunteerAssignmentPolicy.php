<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VolunteerAssignment;

class VolunteerAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view volunteers');
    }

    public function view(User $user, VolunteerAssignment $assignment): bool
    {
        return $user->can('view volunteers');
    }

    public function create(User $user): bool
    {
        return $user->can('create volunteers');
    }

    public function update(User $user, VolunteerAssignment $assignment): bool
    {
        return $user->can('edit volunteers');
    }

    public function delete(User $user, VolunteerAssignment $assignment): bool
    {
        return $user->can('delete volunteers');
    }
}
