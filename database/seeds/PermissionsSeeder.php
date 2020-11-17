<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit cases']);
        Permission::create(['name' => 'delete cases']);
        Permission::create(['name' => 'add cases']);
        Permission::create(['name' => 'view cases']);

        Permission::create(['name' => 'edit lab report']);
        Permission::create(['name' => 'delete lab report']);
        Permission::create(['name' => 'add lab report']);
        Permission::create(['name' => 'view lab report']);

        Permission::create(['name' => 'edit contact tracing']);
        Permission::create(['name' => 'delete contact tracing']);
        Permission::create(['name' => 'add contact tracing']);
        Permission::create(['name' => 'view contact tracing']);


        // create roles and assign existing permissions

        // gets all permissions via Gate::before rule; see AuthServiceProvider to main

        $roleMain = Role::create(['name' => 'main']);
        $roleCenter = Role::create(['name' => 'center']);
        $roleProvince = Role::create(['name' => 'province']);
        $roleDho = Role::create(['name' => 'dho']);
        $roleMunicipality = Role::create(['name' => 'municipality']);
        $roleHealthpost = Role::create(['name' => 'healthpost']);
        $roleHealthworker = Role::create(['name' => 'healthworker']);
        $roleFchv = Role::create(['name' => 'fchv']);


        $roleCenter->givePermissionTo('view cases');
        $roleCenter->givePermissionTo('view lab report');

        $roleProvince->givePermissionTo('view cases');
        $roleProvince->givePermissionTo('view lab report');

        $roleHealthworker->givePermissionTo('view cases');
        $roleHealthworker->givePermissionTo('view lab report');

        $users = \App\User::all();
        foreach ($users as $user){
            switch ($user->role){
                case 'main':
                    $user->assignRole($roleMain);
                    break;
                case 'province':
                    $user->assignRole($roleProvince);
                    break;
                case 'dho':
                    $user->assignRole($roleDho);
                    break;
                case 'municipality':
                    $user->assignRole($roleMunicipality);
                    break;
                case 'healthpost':
                    $user->assignRole($roleHealthpost);
                    break;
                case 'healthworker':
                    $user->assignRole($roleHealthworker);
                    break;
                case 'fchv':
                    $user->assignRole($roleFchv);
                    break;
            }
        }
    }
}
