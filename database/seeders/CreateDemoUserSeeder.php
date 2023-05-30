<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateDemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $user = User::create([
        //     'name' => 'Alphas',
        //     'phone' => '+11230000000',
        //     'password' => bcrypt('Alphas@123')
        // ]);
        // $role = Role::findByName('Coach');
        // $user->assignRole([$role->id]);
    }
}
