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

    protected $user;

    /**
     * PatientcheckupController constructor.
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
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


    /**
     * _Create Patientcheckup_
     *
     * Patientcheckup store endpoint, returns patientcheckup instance. !! token required | patient
     *
     *
     * @bodyParam patient_id int required The patient id associated with call.
     * @bodyParam  doctor_id string required The doctor id associated with call.
     * @bodyParam  start_time string required The datetime indicating starting time of call. Example: "2020-07-10T14:19:24.000000Z"
     * @bodyParam  end_time string required The datetime indicating ending time of call. Example: "2020-07-10T14:40:30.000000Z"
     *
     *
     * @response  201 {
     * "type": "1",
     * "specialization": "cardiology",
     * "updated_at": "2020-07-10T12:16:17.000000Z",
     * "created_at": "2020-07-10T12:16:17.000000Z",
     * "id": 2
     * }
     */
    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }

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
        if (!$newPatientCheckup) {
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


    public function submitCheckupRatings(Request $request, Patientcheckup $patientcheckup)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') ||
            !$this->user->hasRole('doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'doctor_rating' => 'sometimes| numeric| between: 0,5',
            'patient_rating' => 'sometimes| numeric| between: 0,5',
        ]);

        if ($request->has('doctor_rating') || $request->has('patient_rating')) {
            $patientcheckup->doctor_rating = min($request->doctor_rating, 5);
            $patientcheckup->patient_rating = min($request->patient_rating, 5);
            $patientcheckup->save();
        } else {
            return response()->json('no content provided', 422);
        }

        return response()->noContent();
    }

    public function update(Request $request, Patientcheckup $patientcheckup)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') ||
            !$this->user->hasRole('doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }

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
