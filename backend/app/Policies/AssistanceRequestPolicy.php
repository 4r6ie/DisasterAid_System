<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AssistanceRequest;

class AssistanceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view assistance requests');
    }

    public function view(User $user, AssistanceRequest $request): bool
    {
        return $user->can('view assistance requests');
    }

    public function create(User $user): bool
    {
        return $user->can('create assistance requests');
    }

    public function update(User $user, AssistanceRequest $request): bool
    {
        return $user->can('edit assistance requests');
    }

    public function delete(User $user, AssistanceRequest $request): bool
    {
        return $user->can('delete assistance requests');
    }

    public function approve(User $user, AssistanceRequest $request): bool
    {
        return $user->can('approve assistance');
    }

    public function dispatch(User $user, AssistanceRequest $request): bool
    {
        return $user->can('dispatch assistance');
    }

    public function complete(User $user, AssistanceRequest $request): bool
    {
        return $user->can('complete assistance');
    }

    public function reject(User $user, AssistanceRequest $request): bool
    {
        return $user->can('reject assistance');
    }
}