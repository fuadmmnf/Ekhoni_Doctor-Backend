<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorappointment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Handlers\CheckupTransactionHandler;
use App\Patientcheckup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorappointmentController extends Controller
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'doctor_id' => 'required| numeric',
            'patientcheckup_id' => 'required| numeric',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $patientCheckup = Patientcheckup::findOrFail($request->patientcheckup_id);
        $patient = $patientCheckup->patient;

        if ($request->doctor_id != $patientCheckup->doctor->id) {
            return response()->json('doctor_id and patientCheckup doctor id mismatch', 400);
        }

        $newDoctorAppointment = new Doctorappointment();
        $newDoctorAppointment->doctor_id = $doctor->id;

        $checkupTransactionHandler = new CheckupTransactionHandler();

        $newPatientCheckup = $checkupTransactionHandler->createNewCheckup($patient, $doctor, Carbon::parse($request->start_time), Carbon::parse($request->end_time));
        if(!$newPatientCheckup){
            return response()->json('Insufficient Balance', 400);
        }
        $newDoctorAppointment->patientcheckup_id = $patientCheckup->id;

        $newDoctorAppointment->start_time = Carbon::parse($request->start_time);
        $newDoctorAppointment->end_time = Carbon::parse($request->end_time);



        do {
            $code = Str::random(16);
            $doctorAppointment = Doctorappointment::where('code', $code)->first();
        } while ($doctorAppointment);
        $newDoctorAppointment->code = $code;
        $newDoctorAppointment->save();

        return response()->json($newDoctorAppointment, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Doctorappointment $doctorappointment
     * @return \Illuminate\Http\Response
     */
    public function show(Doctorappointment $doctorappointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Doctorappointment $doctorappointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctorappointment $doctorappointment)
    {
        //
    }


    public function update(Request $request, Doctorappointment $doctorappointment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required| numeric',
        ]);
        if ($validator->fails()) {
            return response()->json('validation error', 400);
        }

        $doctorappointment->status = $request->status;
        $doctorappointment->save();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Doctorappointment $doctorappointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctorappointment $doctorappointment)
    {
        //
    }
}
