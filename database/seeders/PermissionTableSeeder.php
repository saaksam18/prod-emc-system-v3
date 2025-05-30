<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'dashboard-list',
           'report-list',
           'operation-list',
           'operation-create',
           'operation-edit',
           'operation-delete',
           'role-list',
           'role-create',
           'role-edit',
           'role-delete',
           'user-list',
           'user-create',
           'user-edit',
           'user-delete',
        ];
      
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}
