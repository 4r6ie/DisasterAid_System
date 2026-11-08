<?php

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(
    TestCase::class,
    RefreshDatabase::class,
)->in('Feature');

/**
 * Create a user holding the given permissions.
 *
 * @param  array<int, string>  $permissions
 */
function makeUserWithPermissions(array $permissions): User
{
    $user = User::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}
