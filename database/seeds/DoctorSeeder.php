<?php

use App\Http\Controllers\Handlers\TokenUserHandler;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctortypes = ['general practitioner', 'cardiologist', 'homeopathy'];
        foreach ($doctortypes as $doctortype) {
            \App\Doctortype::create([
                'name' => $doctortype
            ]);
        }

        $user_role = 'doctor';


        $mobile = '01156572071';
        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($mobile);
        $newDoctor = new \App\Doctor();
        $newDoctor->user_id = $user->id;
        $newDoctor->doctortype_id = 1;
        $newDoctor->name = 'fuad';
        $newDoctor->email = 'doctor1@ekhonidoctor.com';
        $newDoctor->bmdc_number = '1111111';
        $newDoctor->payment_style = 1;
        $newDoctor->activation_status = 1;
        $newDoctor->rate = 100;
        $newDoctor->offer_rate = 80;
        $newDoctor->first_appointment_rate = 150;
        $newDoctor->gender = 0;
        $newDoctor->password = 'doctor123';
        $newDoctor->save();

        $user->assignRole($user_role);
    }
}
