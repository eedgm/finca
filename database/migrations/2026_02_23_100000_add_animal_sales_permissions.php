<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $names = [
            'list animal-sales',
            'view animal-sales',
            'create animal-sales',
            'update animal-sales',
            'delete animal-sales',
        ];

        foreach ($names as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $permissions = Permission::whereIn('name', $names)->pluck('name');

        foreach (['user', 'super-admin'] as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }

    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $names = [
            'list animal-sales',
            'view animal-sales',
            'create animal-sales',
            'update animal-sales',
            'delete animal-sales',
        ];

        Permission::whereIn('name', $names)->where('guard_name', 'web')->delete();
    }
};
