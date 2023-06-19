<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateInitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
            'phone' => '+91 9188305778',
            'address' => 'Bangalore',
            'status' => '1',
            'is_email_verified' => '1'
        ]);

        $role = Role::where('name', 'Super Admin')->first();
        $user->assignRole([$role->id]);
    }
}
