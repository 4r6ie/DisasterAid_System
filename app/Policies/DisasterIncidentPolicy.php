<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DisasterIncident;

class DisasterIncidentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view disasters');
    }

    public function view(User $user, DisasterIncident $incident): bool
    {
        return $user->can('view disasters');
    }

    public function create(User $user): bool
    {
        return $user->can('create disasters');
    }

    public function update(User $user, DisasterIncident $incident): bool
    {
        return $user->can('edit disasters');
    }

    public function delete(User $user, DisasterIncident $incident): bool
    {
        return $user->can('delete disasters');
    }

    public function assignResponder(User $user, DisasterIncident $incident): bool
    {
        return $user->can('assign responders');
    }

    public function updateStatus(User $user, DisasterIncident $incident): bool
    {
        return $user->can('update disaster status');
    }

    public function updateCasualties(User $user, DisasterIncident $incident): bool
    {
        return $user->can('update casualties');
    }
}