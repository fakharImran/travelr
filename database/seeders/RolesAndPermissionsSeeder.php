<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Roles
         Role::create(['name' => 'admin']);
         Role::create(['name' => 'appuser']);
         Role::create(['name' => 'driver']);
         Role::create(['name' => 'dispatch']);

         // Permissions
         Permission::create(['name' => 'mobile']);
         Permission::create(['name' => 'driver-mobile']);
         Permission::create(['name' => 'website']);
         Permission::create(['name' => 'admin']);

         // Assign permissions to roles
         $adminRole = Role::findByName('admin');
         $adminRole->givePermissionTo(['admin', 'website', 'mobile', 'driver-mobile']);

         $appuser = Role::findByName('appuser');
         $appuser->givePermissionTo('mobile');

         $driverRole = Role::findByName('driver');
         $driverRole->givePermissionTo('driver-mobile');
    }
}
