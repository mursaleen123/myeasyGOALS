<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Portal Admin',
            'email' => 'admin@portal.com',
            'password' => bcrypt('demo@123')
        ]);

        $role = Role::findByName('Super Admin');
        $user->assignRole([$role->id]);
    }
}
