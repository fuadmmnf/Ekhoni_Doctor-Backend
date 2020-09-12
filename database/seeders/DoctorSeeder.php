<?php

namespace Database\Seeders;

use App\Http\Controllers\Handlers\TokenUserHandler;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctortypes = ['cardiology', 'homeopathy', 'child specialist',
            'dentist', 'diabetes', 'eye specialist', 'gastroenterology',
            'gynecology', 'hematology', 'homeopathy', 'medicine specialist',
            'neurology', 'orthopedics', 'psychiatry', 'rhinology', 'urology'];
        foreach ($doctortypes as $doctortype) {
            \App\Doctortype::create([
                'name' => $doctortype,
                'monogram' => 'images/' . str_replace(' ', '_', $doctortype) . '.png'
            ]);
        }

        $user_role = 'doctor';


        $mobile = '8801156572071';
        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($mobile, '');
        $newDoctor = new \App\Doctor();
        $newDoctor->user_id = $user->id;
        $newDoctor->doctortype_id = 1;
        $newDoctor->name = 'fuad';
        $newDoctor->email = 'doctor1@ekhonidoctor.com';
        $newDoctor->bmdc_number = '1111111';
        $newDoctor->payment_style = 1;
        $newDoctor->activation_status = 1;
        $newDoctor->commission = 0.23;
        $newDoctor->rate = 100;
        $newDoctor->offer_rate = 80;
        $newDoctor->followup_rate = 80;
        $newDoctor->report_followup_rate = 50;
        $newDoctor->gender = 0;
        $newDoctor->medical_college = "dmc";
        $newDoctor->save();

        $user->password = Hash::make('doctor123');
        unset($user->token);
        $user->save();

        $user->assignRole($user_role);
    }
}
