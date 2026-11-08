<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EmergencyReport;

class EmergencyReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view emergencies');
    }

    public function view(User $user, EmergencyReport $report): bool
    {
        return $user->can('view emergencies');
    }

    public function create(User $user): bool
    {
        return $user->can('create emergencies');
    }

    public function update(User $user, EmergencyReport $report): bool
    {
        return $user->can('edit emergencies');
    }

    public function delete(User $user, EmergencyReport $report): bool
    {
        return $user->can('delete emergencies');
    }

    public function assignResponder(User $user, EmergencyReport $report): bool
    {
        return $user->can('assign responders');
    }

    public function updateStatus(User $user, EmergencyReport $report): bool
    {
        return $user->can('update emergency status');
    }
}