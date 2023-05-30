<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = Permission::create(['name' => 'default-dashboard-view']);
        $roles = Role::all();
        foreach ($roles as  $role) {
            $role->givePermissionTo($permission->name);
        }
    }
}
