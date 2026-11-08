<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AffectedPerson;

class AffectedPersonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view affected persons');
    }

    public function view(User $user, AffectedPerson $person): bool
    {
        return $user->can('view affected persons');
    }

    public function create(User $user): bool
    {
        return $user->can('create affected persons');
    }

    public function update(User $user, AffectedPerson $person): bool
    {
        return $user->can('edit affected persons');
    }

    public function delete(User $user, AffectedPerson $person): bool
    {
        return $user->can('delete affected persons');
    }

    public function updateCondition(User $user, AffectedPerson $person): bool
    {
        return $user->can('update condition');
    }

    public function assignEvacuationCenter(User $user, AffectedPerson $person): bool
    {
        return $user->can('assign evacuation center');
    }
}