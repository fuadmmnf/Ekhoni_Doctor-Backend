<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getPatientsByUser(User $user){
        $userPatients = Patient::where('user_id', $user->id)->get();
        return response()->json($userPatients);
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
            'user_id' => 'required| numeric',
            'name' => 'required',
            'age' => 'required| numeric',
            'gender' => 'required| numeric',
            'blood_group' => 'sometimes| nullable',
            'blood_pressure' => 'sometimes| nullable',
            'cholesterol_level' => 'sometimes| nullable',
        ]);

        $user = User::findOrFail($request->user_id);
        $newPatient = new Patient();
        $newPatient->user_id = $user->id;
        $newPatient->name = $request->name;
        $newPatient->age = $request->age;
        $newPatient->gender = $request->gender;

        do
        {
            $code = Str::random(16);
            $patient = Patient::where('code', $code)->first();
        }
        while($patient);
        $newPatient->code = $code;

        if($request->has('blood_group')){
            $newPatient->blood_group = $request->blood_group;
        }
        if($request->has('blood_pressure')){
            $newPatient->blood_pressure = $request->blood_pressure;
        }
        if($request->has('cholesterol_level')){
            $newPatient->cholesterol_level = $request->cholesterol_level;
        }
        $newPatient->save();

        return response()->json($newPatient, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $this->validate($request, [
            'age' => 'sometimes| numeric',
            'blood_group' => 'sometimes| nullable',
            'blood_pressure' => 'sometimes| nullable',
            'cholesterol_level' => 'sometimes| nullable',
        ]);

        if($request->has('age')) {
            $patient->age = $request->age;
        }
        if($request->has('blood_group')){
            $patient->blood_group = $request->blood_group;
        }
        if($request->has('blood_pressure')){
            $patient->blood_pressure = $request->blood_pressure;
        }
        if($request->has('cholesterol_level')){
            $patient->cholesterol_level = $request->cholesterol_level;
        }
        $patient->save();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
