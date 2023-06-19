<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'price' => '1',
            'validity' => '1825',
            'is_default' => '1',
        ]);
        $permissions = Permission::select('id')
        ->where('name', 'not like', 'tax-rate-%')
        ->get()->pluck('id');
        $role->syncPermissions($permissions);

        $role = Role::create([
            'name' => 'Admin',
            'guard_name' => 'web',
            'role_for' => '1',
            'is_default' => '1',
        ]);
        $permissions = Permission::select('id')
            ->where('name', 'not like', 'role-%')
            ->where('name', 'not like', 'company-%')
            ->where('name', 'not like', 'tax-rate-%')
            ->get()->pluck('id');
        $role->syncPermissions($permissions);

        $role = Role::create([
            'name' => 'Employee',
            'guard_name' => 'web',
            'role_for' => '1',
            'is_default' => '1',
        ]);

        $role = Role::create([
            'name' => 'Student',
            'guard_name' => 'web',
            'role_for' => '1',
            'is_default' => '1',
        ]);

    }
}
