<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            // 'sport-create',
            // 'sport-list',
            // 'sport-edit',
            // 'sport-delete',

            // 'sport-position-create',
            // 'sport-position-list',
            // 'sport-position-edit',
            // 'sport-position-delete',

            'player-file-create',
            'player-file-list',
            'player-file-edit',
            'player-file-delete',

            'scan-user-create',
            'scan-user-list',
            'scan-user-edit',
            'scan-user-delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
        $role = Role::findByName('Super Admin', 'web');
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
    }
}
