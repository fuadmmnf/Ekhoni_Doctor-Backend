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
        $doctortypes = ['General Practitioner', 'Cardiologist', 'Child Specialist',
            'Dentist', 'Dermatologist & Venereologist', 'Diabetes', 'Dietitian & Nutritionist', 'Ear, Nose & Throat (ENT) Specialist', 'Eye Specialist', 'Gastroenterologist',
            'Gynecologist', 'Homeopathy', 'Medicine Specialist',
            'Nephrologist', 'Neurologist', 'Orthopedics', 'Psychiatrist',];

        $doctortypesBangla = ['এম.বি.বি.এস ডাক্তার', 'হৃদরোগ বিশেষজ্ঞ', 'শিশু বিশেষজ্ঞ',
            'দন্ত চিকিৎসক', 'চর্ম ও যৌন রোগ বিশেষজ্ঞ', 'ডাইবেটিস', 'পুস্টিবিদ এবং ডাইটেশিয়ান', 'নাক, কান, গলা বিশেষজ্ঞ', 'চক্ষু বিশেষজ্ঞ', 'পেট ও অন্ত্র বিশেষজ্ঞ',
            'স্ত্রী রোগ বিশেষজ্ঞ', 'হোমিওপ্যাথি', 'মেডিসন বিশেষজ্ঞ', 'কিডনী বিশেষজ্ঞ',
            'স্নায়ু রোগ বিশেষজ্ঞ', 'অর্থোপেডিক সার্জন', 'মনরোগ বিশেষজ্ঞ',];
        for($i=0; $i<count($doctortypes); $i++){
            \App\Doctortype::create([
                'name' => $doctortypes[$i],
                'name_bangla' => $doctortypesBangla[$i],
                'monogram' => 'images/' . str_replace(' ', '_', strtolower($doctortypes[$i])) . '.png'
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
