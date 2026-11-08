<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ReliefItem;

class ReliefItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view relief inventory');
    }

    public function view(User $user, ReliefItem $item): bool
    {
        return $user->can('view relief inventory');
    }

    public function create(User $user): bool
    {
        return $user->can('create relief items');
    }

    public function update(User $user, ReliefItem $item): bool
    {
        return $user->can('edit relief items');
    }

    public function delete(User $user, ReliefItem $item): bool
    {
        return $user->can('delete relief items');
    }

    public function stockIn(User $user, ReliefItem $item): bool
    {
        return $user->can('manage inventory');
    }

    public function stockOut(User $user, ReliefItem $item): bool
    {
        return $user->can('manage inventory');
    }

    public function distribute(User $user, ReliefItem $item): bool
    {
        return $user->can('distribute relief');
    }
}