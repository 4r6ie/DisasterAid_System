<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permissions grouped by the module that owns them. The names must match
     * the ones referenced by the policies in App\Policies and the Gate
     * abilities registered in AppServiceProvider.
     */
    protected array $permissions = [
        'dashboard' => [
            'view dashboard',
        ],
        'disasters' => [
            'view disasters',
            'create disasters',
            'edit disasters',
            'delete disasters',
            'assign responders',
            'update disaster status',
            'update casualties',
        ],
        'emergencies' => [
            'view emergencies',
            'create emergencies',
            'edit emergencies',
            'delete emergencies',
            'update emergency status',
        ],
        'assistance' => [
            'view assistance requests',
            'create assistance requests',
            'edit assistance requests',
            'delete assistance requests',
            'approve assistance',
            'dispatch assistance',
            'complete assistance',
            'reject assistance',
        ],
        'evacuation' => [
            'view evacuation centers',
            'create evacuation centers',
            'edit evacuation centers',
            'delete evacuation centers',
            'update occupancy',
        ],
        'inventory' => [
            'view relief inventory',
            'create relief items',
            'edit relief items',
            'delete relief items',
            'manage inventory',
            'distribute relief',
        ],
        'responders' => [
            'view responders',
            'create responders',
            'edit responders',
            'delete responders',
            'update responder status',
        ],
        'volunteers' => [
            'view volunteers',
            'create volunteers',
            'edit volunteers',
            'delete volunteers',
            'update volunteer status',
        ],
        'affected' => [
            'view affected persons',
            'create affected persons',
            'edit affected persons',
            'delete affected persons',
            'update condition',
            'assign evacuation center',
        ],
        'announcements' => [
            'view announcements',
            'create announcements',
            'edit announcements',
            'delete announcements',
            'publish announcements',
        ],
        'audit' => [
            'view audit logs',
        ],
    ];

    /**
     * Roles and the permission groups they are granted.
     */
    protected array $roles = [
        'super_admin' => [
            'dashboard', 'disasters', 'emergencies', 'assistance', 'evacuation',
            'inventory', 'responders', 'volunteers', 'affected', 'announcements', 'audit',
        ],
        'admin' => [
            'dashboard', 'disasters', 'emergencies', 'assistance', 'evacuation',
            'inventory', 'responders', 'volunteers', 'affected', 'announcements',
            'audit',
        ],
        'responder' => [
            'dashboard', 'disasters', 'emergencies', 'assistance', 'evacuation',
            'inventory', 'affected', 'announcements',
        ],
        'volunteer' => [
            'dashboard', 'disasters', 'assistance', 'evacuation', 'inventory', 'announcements',
        ],
        'user' => [
            'dashboard', 'disasters', 'assistance', 'announcements',
        ],
    ];

    public function run(): void
    {
        // The permission cache is not warmed inside tests until the first
        // lookup, and schema operations can leave it stale.
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissionIds = [];

        foreach ($this->permissions as $group => $names) {
            foreach ($names as $name) {
                $permission = Permission::firstOrCreate(
                    ['name' => $name],
                    ['guard_name' => 'web'],
                );

                $permissionIds[$name] = $permission->id;
            }
        }

        foreach ($this->roles as $roleName => $groups) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web'],
            );

            $names = array_merge(...array_map(
                fn (string $group) => $this->permissions[$group],
                $groups
            ));

            $role->syncPermissions(array_map(
                fn (string $name) => $permissionIds[$name],
                $names
            ));
        }

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
