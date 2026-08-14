<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Announcement;

class AnnouncementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view announcements');
    }

    public function view(User $user, Announcement $announcement): bool
    {
        return $user->can('view announcements');
    }

    public function create(User $user): bool
    {
        return $user->can('create announcements');
    }

    public function update(User $user, Announcement $announcement): bool
    {
        return $user->can('edit announcements');
    }

    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->can('delete announcements');
    }

    public function publish(User $user, Announcement $announcement): bool
    {
        return $user->can('publish announcements');
    }
}