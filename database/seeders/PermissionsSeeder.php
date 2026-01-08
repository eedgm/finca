<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create default permissions for current project modules
        
        // Cows (Vacas) permissions
        Permission::create(['name' => 'list cows']);
        Permission::create(['name' => 'view cows']);
        Permission::create(['name' => 'create cows']);
        Permission::create(['name' => 'update cows']);
        Permission::create(['name' => 'delete cows']);

        // Cow Types (Tipos de Vaca) permissions
        Permission::create(['name' => 'list cow-types']);
        Permission::create(['name' => 'view cow-types']);
        Permission::create(['name' => 'create cow-types']);
        Permission::create(['name' => 'update cow-types']);
        Permission::create(['name' => 'delete cow-types']);

        // Breeds (Razas) permissions
        Permission::create(['name' => 'list breeds']);
        Permission::create(['name' => 'view breeds']);
        Permission::create(['name' => 'create breeds']);
        Permission::create(['name' => 'update breeds']);
        Permission::create(['name' => 'delete breeds']);

        // Farms (Fincas) permissions
        Permission::create(['name' => 'list farms']);
        Permission::create(['name' => 'view farms']);
        Permission::create(['name' => 'create farms']);
        Permission::create(['name' => 'update farms']);
        Permission::create(['name' => 'delete farms']);

        // Histories (Historiales) permissions
        Permission::create(['name' => 'list histories']);
        Permission::create(['name' => 'view histories']);
        Permission::create(['name' => 'create histories']);
        Permission::create(['name' => 'update histories']);
        Permission::create(['name' => 'delete histories']);

        // Manufacturers (Fabricantes) permissions
        Permission::create(['name' => 'list manufacturers']);
        Permission::create(['name' => 'view manufacturers']);
        Permission::create(['name' => 'create manufacturers']);
        Permission::create(['name' => 'update manufacturers']);
        Permission::create(['name' => 'delete manufacturers']);

        // Markets (Tiendas) permissions
        Permission::create(['name' => 'list markets']);
        Permission::create(['name' => 'view markets']);
        Permission::create(['name' => 'create markets']);
        Permission::create(['name' => 'update markets']);
        Permission::create(['name' => 'delete markets']);

        // Medicines (Medicinas) permissions
        Permission::create(['name' => 'list medicines']);
        Permission::create(['name' => 'view medicines']);
        Permission::create(['name' => 'create medicines']);
        Permission::create(['name' => 'update medicines']);
        Permission::create(['name' => 'delete medicines']);

        // Solds (Ventas) permissions
        Permission::create(['name' => 'list solds']);
        Permission::create(['name' => 'view solds']);
        Permission::create(['name' => 'create solds']);
        Permission::create(['name' => 'update solds']);
        Permission::create(['name' => 'delete solds']);

        Permission::create(['name' => 'list materials']);
        Permission::create(['name' => 'view materials']);
        Permission::create(['name' => 'create materials']);
        Permission::create(['name' => 'update materials']);
        Permission::create(['name' => 'delete materials']);

        Permission::create(['name' => 'list inventory materials']);
        Permission::create(['name' => 'view inventory materials']);
        Permission::create(['name' => 'create inventory materials']);
        Permission::create(['name' => 'update inventory materials']);
        Permission::create(['name' => 'delete inventory materials']);

        // Create user role and assign existing permissions
        $currentPermissions = Permission::all();
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo($currentPermissions);

        // Create admin exclusive permissions
        Permission::create(['name' => 'list roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);

        Permission::create(['name' => 'list permissions']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'update permissions']);
        Permission::create(['name' => 'delete permissions']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        // Create admin role and assign all permissions
        $allPermissions = Permission::all();
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo($allPermissions);

        $user = \App\Models\User::whereEmail('admin@admin.com')->first();

        if ($user) {
            $user->assignRole($adminRole);
        }
    }
}
