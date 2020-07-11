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
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }


    /**
     * _Create Doctorappointment_
     *
     * Doctorappointment store endpoint, User must have sufficient balance for doctor rate, returns doctorappointment instance. !! token required | patient
     *
     *
     * @bodyParam patientcheckup_id int required The patientcheckup id associated with appointment. Frontend must create patientcheckup(with blank start_time and end_time) instance prior to creating doctorappointment.
     * @bodyParam  start_time string required The datetime indicating starting time of scheduled appointment. Example: "2020-07-10T14:19:24.000000Z"
     * @bodyParam  end_time string required The datetime indicating ending time of scheduled appointment. Example: "2020-07-10T14:40:30.000000Z"
     *
     *
     * @response  201 {
     * "doctor_id": 6,
     * "patientcheckup_id": 2,
     * "start_time": "2020-07-14T14:19:24.000000Z",
     * "end_time": "2020-07-14T14:40:24.000000Z",
     * "code": "fyFDiwwuVU2pzlO8",
     * "updated_at": "2020-07-11T11:51:21.000000Z",
     * "created_at": "2020-07-11T11:51:21.000000Z",
     * "id": 1
     * }
     *
     * @response 400 "User associated with token does not have patient associated with checkup"
     *
     */
    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
//            'doctor_id' => 'required| numeric',
            'patientcheckup_id' => 'required| numeric',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

//        $doctor = Doctor::findOrFail($request->doctor_id);
        $patientCheckup = Patientcheckup::findOrFail($request->patientcheckup_id);
        $patient = $patientCheckup->patient;
        $doctor = $patientCheckup->doctor;
        $transaction = $patientCheckup->transaction;


        if ($this->user->id != $patient->user->id) {
            return response()->json('User associated with token does not have patient associated with checkup', 400);
        }
//        if ($request->doctor_id != $patientCheckup->doctor->id) {
//            return response()->json('doctor_id and patientCheckup doctor id mismatch', 400);
//        }

        $newDoctorAppointment = new Doctorappointment();
        $newDoctorAppointment->doctor_id = $doctor->id;
        $newDoctorAppointment->patientcheckup_id = $patientCheckup->id;
        $newDoctorAppointment->start_time = Carbon::parse($request->start_time);
        $newDoctorAppointment->end_time = Carbon::parse($request->end_time);

        do {
            $code = Str::random(16);
            $doctorAppointment = Doctorappointment::where('code', $code)->first();
        } while ($doctorAppointment);
        $newDoctorAppointment->code = $code;
        $newDoctorAppointment->save();

        $transaction->status = 1;
        $transaction->save();

        $doctor->booking_start_time = null;
        $doctor->save();

        return response()->json($newDoctorAppointment, 201);
    }


    /**
     * _Update Doctorappointment_
     *
     * Doctorappointment update, change appointment status. !! token required | doctor
     *
     *
     * @urlParam doctorappointment int required The appointment id.
     * @bodyParam status int string Required Indicates status of appointment. 0 => active, 1 => canceled, 2 => finished
     *
     * @response  204
     *
     */
    public function update(Request $request, Doctorappointment $doctorappointment)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'status' => 'required| numeric',
        ]);

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
