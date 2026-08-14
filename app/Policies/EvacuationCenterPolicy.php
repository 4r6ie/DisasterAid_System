<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EvacuationCenter;

class EvacuationCenterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view evacuation centers');
    }

    public function view(User $user, EvacuationCenter $center): bool
    {
        return $user->can('view evacuation centers');
    }

    public function create(User $user): bool
    {
        return $user->can('create evacuation centers');
    }

    public function update(User $user, EvacuationCenter $center): bool
    {
        return $user->can('edit evacuation centers');
    }

    public function delete(User $user, EvacuationCenter $center): bool
    {
        return $user->can('delete evacuation centers');
    }

    public function updateOccupancy(User $user, EvacuationCenter $center): bool
    {
        return $user->can('update occupancy');
    }
}