<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Responder;

class ResponderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view responders');
    }

    public function view(User $user, Responder $responder): bool
    {
        return $user->can('view responders');
    }

    public function create(User $user): bool
    {
        return $user->can('create responders');
    }

    public function update(User $user, Responder $responder): bool
    {
        return $user->can('edit responders');
    }

    public function delete(User $user, Responder $responder): bool
    {
        return $user->can('delete responders');
    }

    public function updateStatus(User $user, Responder $responder): bool
    {
        return $user->can('update responder status');
    }
}