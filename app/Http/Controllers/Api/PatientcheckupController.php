<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Handlers\CheckupTransactionHandler;
use App\Http\Controllers\Handlers\PushNotificationHandler;
use App\Patient;
use App\Patientcheckup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group  Patientcheckup management
 *
 * APIs related to doctor calls for patient checkup
 */
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



//    public function getPatientCheckupsByPatient(Patient $patient)
//    {
//        $checkupsByPatient = Patientcheckup::where('patient_id', $patient->id)->get();
//        return response()->json($checkupsByPatient);
//    }
//
//    public function getPatientCheckupsByDoctor(Doctor $doctor)
//    {
//        $checkupsByDoctor = Patientcheckup::where('doctor_id', $doctor->id)->get();
//        return response()->json($checkupsByDoctor);
//    }



    /**
     * _Create Patientcheckup_
     *
     * Patientcheckup store endpoint, User must have sufficient balance for doctor rate, returns patientcheckup instance. !! token required | patient
     *
     *
     * @bodyParam patient_id int required The patient id associated with call.
     * @bodyParam  doctor_id string required The doctor id associated with call.
     * @bodyParam  start_time string required The datetime indicating starting time of call. Can be set blank to indicate checkup instance for doctorappointment. Example: "", "2020-07-10T14:19:24.000000Z"
     * @bodyParam  end_time string required The datetime indicating ending time of call. Can be set blank to indicate start of checkup. Example: "", "2020-07-10T14:40:30.000000Z"
     *
     *
     * @response  201 {
     * "patient_id": 1,
     * "doctor_id": 6,
     * "start_time": "2020-07-10T21:30:47.000000Z",
     * "end_time": null,
     * "transaction_id": 5,
     * "code": "UenaBBVXuQF2F7A4",
     * "updated_at": "2020-07-11T09:46:43.000000Z",
     * "created_at": "2020-07-11T09:46:43.000000Z",
     * "id": 1
     * }
     * @response 400 "Insufficient Balance"
     *
     *
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
            'start_time' => 'present| nullable',
            'end_time' => 'present| nullable',
        ]);

        $patient = Patient::where('id', $request->patient_id)
            ->where('user_id', $this->user->id)->first();
        if(!$patient){
            return response()->json('No patient selected associated with user', 400);
        }



        $doctor = Doctor::findOrFail($request->doctor_id);
        $checkupTransactionHandler = new CheckupTransactionHandler();

        $newPatientCheckup = $checkupTransactionHandler->createNewCheckup($patient, $doctor, (strlen($request->start_time) == 0)? null: Carbon::parse($request->start_time), (strlen($request->end_time) == 0) ? null : Carbon::parse($request->end_time));
        if (!$newPatientCheckup) {
            return response()->json('Insufficient Balance', 400);
        }

        return response()->json($newPatientCheckup, 201);
    }


    /**
     * _Update Checkup_
     *
     * Patientcheckup update patient and doctor ratings and endtime. !! token required | patient, doctor
     *
     *
     * @urlParam patientcheckup required The patientcheckup id.
     * @bodyParam end_time int string Call end time. Example: "2020-07-10T21:45:47.000000Z"
     * @bodyParam doctor_rating int The doctor service rating provided by patient [0-5].
     * @bodyParam  patient_rating int The patient behavior rating provided by doctor [0-5].
     *
     * @response  204
     *
     *
     */
    public function update(Request $request, Patientcheckup $patientcheckup)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') &&
            !$this->user->hasRole('doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'end_time' => 'required',
            'doctor_rating' => 'sometimes| numeric| between: 0,5',
            'patient_rating' => 'sometimes| numeric| between: 0,5',
        ]);

        if ($patientcheckup->end_time == null) {
            $patientcheckup->end_time = Carbon::parse($request->end_time);
        }
        if ($request->has('doctor_rating')) {
            $patientcheckup->doctor_rating = min($request->doctor_rating, 5);
        }

        if ($request->has('patient_rating')) {
            $patientcheckup->patient_rating = min($request->patient_rating, 5);
        }

        $patientcheckup->save();

        return response()->noContent();
    }



    public function sendCheckupCallNotification(Patientcheckup $patientcheckup){
        $pushNotificationHandler = new PushNotificationHandler();
        $pushNotificationHandler->sendNotificationToSpecificUser($patientcheckup->patient->user, "Incoming Call");
    }
}
