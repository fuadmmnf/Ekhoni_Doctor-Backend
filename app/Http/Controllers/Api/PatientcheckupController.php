<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Handlers\CheckupTransactionHandler;
use App\Patient;
use App\Patientcheckup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientcheckupController extends Controller
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


    public function getPatentCheckupsByPatient(Patient $patient)
    {
        $checkupsByPatient = Patientcheckup::where('patient_id', $patient->id)->get();
        return response()->json($checkupsByPatient);
    }

    public function getPatentCheckupsByDoctor(Doctor $doctor)
    {
        $checkupsByDoctor = Patientcheckup::where('doctor_id', $doctor->id)->get();
        return response()->json($checkupsByDoctor);
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
            'patient_id' => 'required| numeric',
            'doctor_id' => 'required| numeric',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $patient = Patient::findOrFail($request->patient_id);
        $doctor = Doctor::findOrFail($request->doctor_id);
        $checkupTransactionHandler = new CheckupTransactionHandler();

        $newPatientCheckup = $checkupTransactionHandler->createNewCheckup($patient, $doctor, Carbon::parse($request->start_time), Carbon::parse($request->end_time));
        if(!$newPatientCheckup){
            return response()->json('Insufficient Balance', 400);
        }

        return response()->json($newPatientCheckup, 201);


    }

    /**
     * Display the specified resource.
     *
     * @param \App\Patientcheckup $patientcheckup
     * @return \Illuminate\Http\Response
     */
    public function show(Patientcheckup $patientcheckup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Patientcheckup $patientcheckup
     * @return \Illuminate\Http\Response
     */
    public function edit(Patientcheckup $patientcheckup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Patientcheckup $patientcheckup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patientcheckup $patientcheckup)
    {
        $this->validate($request, [
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $patientcheckup->start_time = Carbon::parse($request->start_time);
        $patientcheckup->end_time = Carbon::parse($request->end_time);
        $patientcheckup->save();
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Patientcheckup $patientcheckup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patientcheckup $patientcheckup)
    {
        //
    }
}
