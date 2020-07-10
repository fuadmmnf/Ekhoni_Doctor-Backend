<?php

use App\Admin;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        $role_doctor_admin = Role::create(['name' => 'admin:doctor']);
        $role_report_admin = Role::create(['name' => 'admin:report']);
        $role_user_admin = Role::create(['name' => 'admin:user']);
        $role_transaction_admin = Role::create(['name' => 'admin:transaction']);
        $role_patientcheckup_admin = Role::create(['name' => 'admin:patientcheckup']);
        $role_doctor = Role::create(['name' => 'doctor']);
        $role_patient = Role::create(['name' => 'patient']);


        $permission_admins = Permission::create(['name' => 'alter:admins']);
        $permission_admins->assignRole($role_super_admin);

        $permission_doctors = Permission::create(['name' => 'alter:doctors']);
        $permission_doctors->syncRoles([$role_super_admin, $role_doctor_admin]);

        $permission_transactions = Permission::create(['name' => 'alter:transactions']);
        $permission_transactions->syncRoles([$role_super_admin, $role_transaction_admin]);

        $permission_patients = Permission::create(['name' => 'alter:patients']);
        $permission_patients->syncRoles([$role_super_admin, $role_user_admin]);

        $permission_checkups = Permission::create(['name' => 'alter:checkups']);
        $permission_checkups->syncRoles([$role_super_admin, $role_patientcheckup_admin]);

        $permission_appointments = Permission::create(['name' => 'alter:reports']);
        $permission_appointments->syncRoles([$role_super_admin, $role_report_admin]);


        $name = 'fuad';
        $email = 'fuadmmnf@gmail.com';
        $password = 'fuadmmnf';
        $mobile = '01956572070';
        $user_role = 'super_admin';
        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($mobile);
        $newAdmin = new Admin();
        $newAdmin->user_id = $user->id;
        $newAdmin->name = $name;
        $newAdmin->email = $email;
        $newAdmin->password = Hash::make($password);
        $newAdmin->save();

        $user->assignRole($user_role);
        $newAdmin->token = $user->token;


    }
}
