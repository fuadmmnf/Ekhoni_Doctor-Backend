<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_super_admin = Role::create(['name' => 'super_admin']);
        $role_admin = Role::create(['name' => 'admin']);
        $role_doctor = Role::create(['name' => 'doctor']);
        $role_patient = Role::create(['name' => 'patient']);


        $permission_admins = Permission::create(['name' => 'alter:admins']);
        $permission_admins->assignRole($role_super_admin);

        $permission_doctors = Permission::create(['name' => 'alter:doctors']);
        $permission_doctors->syncRoles([$role_super_admin, $role_admin]);

        $permission_transactions = Permission::create(['name' => 'alter:transactions']);
        $permission_transactions->syncRoles([$role_super_admin, $role_admin]);

        $permission_patients = Permission::create(['name' => 'alter:patients']);
        $permission_patients->syncRoles([$role_super_admin, $role_admin]);

        $permission_checkups = Permission::create(['name' => 'alter:checkups']);
        $permission_checkups->syncRoles([$role_super_admin, $role_admin]);

        $permission_appointments = Permission::create(['name' => 'alter:appointments']);
        $permission_appointments->syncRoles([$role_super_admin, $role_admin]);





    }
}
