<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctortype;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'doctortype_id' => 'required| numeric',
            'name' => 'required',
            'bmdc_number' => 'required| unique:doctors',
            'rate' => 'required| numeric',
            'offer_rate' => 'sometimes| numeric',
            'gender' => 'required| numeric',
            'mobile' => 'required| unique:doctors| min: 11| max: 14',
            'email' => 'required',
            'workplace' => 'required',
            'designation' => 'required',
            'medical_college' => 'required',
            'others_training' => 'required',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);

        $doctorType = Doctortype::findOrFail($request->doctortype_id);

        $newDoctor = new Doctor();
        $newDoctor->name = $request->name;
        $newDoctor->bmdc_number = $request->bmdc_number;
        $newDoctor->rate = $request->rate;
        //activation_status check by admin role
        $newDoctor->offer_rate = ($request->has('offer_rate'))? $request->offer_rate: $request->rate;
        $newDoctor->gender = $request->gender;
        $newDoctor->mobile = $request->mobile;
        $newDoctor->email = $request->email;
        $newDoctor->workplace = $request->workplace;
        $newDoctor->designation = $request->designation;
        $newDoctor->medical_college = $request->medical_college;
        $newDoctor->others_training = $request->others_training;
        $newDoctor->starting_time = ($request->has('starting_time'))? Carbon::parse($request->starting_time): null;
        $newDoctor->end_time = ($request->has('end_time'))? Carbon::parse($request->end_time): null;
        $newDoctor->max_appointments_per_day = ($request->has('max_appointments_per_day'))? $request->max_appointments_per_day: null;
        $newDoctor->save();

        return response()->json($newDoctor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }


    public function update(Request $request, Doctor $doctor)
    {
        $this->validate($request, [
            'rate' => 'sometimes| numeric',
            'offer_rate' => 'sometimes| numeric',
            'workplace' => 'sometimes',
            'designation' => 'sometimes',
            'medical_college' => 'required',
            'others_training' => 'required',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
