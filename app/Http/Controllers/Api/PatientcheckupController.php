<?php

namespace App\Http\Controllers\Api;

use App\Checkupprescription;
use App\Doctor;
use App\Doctorappointment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Handlers\Checkup\CheckupCallHandler;
use App\Http\Controllers\Handlers\Checkup\CheckupTransactionHandler;
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


    public function getDetailsFromCode(Patientcheckup $patientcheckup)
    {
        $patientcheckup->patient;
        $patientcheckup->patient->patientprescriptions;
        return response()->json($patientcheckup, 200);
    }




//    public function getPatientCheckupsByPatient(Patient $patient)
//    {
//        $checkupsByPatient = Patientcheckup::where('patient_id', $patient->id)->load('doctor')->paginate(20);
//        return response()->json($checkupsByPatient);
//    }

    public function getPatientCheckupsByDoctor(Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }


        $checkupsByDoctorIds = Patientcheckup::where('doctor_id', $doctor->id)
            ->whereNotNull('start_time')
            ->pluck('id');

        $checkupPrescriptionDone = Checkupprescription::where('status', 1)
            ->whereIn('patientcheckup_id', $checkupsByDoctorIds)
            ->paginate(20);
        $checkupPrescriptionDone->getCollection()->transform(function ($checkupPrescription) {
            $checkup = $checkupPrescription->patientcheckup;
            unset($checkupPrescription->patientcheckup);
            return [
                "id" => $checkup->id,
                "start_time" => $checkup->start_time,
                "patient" => $checkup->patient,
                "amount" => $checkup->transaction->amount,
                "checkupprescription" => $checkupPrescription
            ];
        });
        return response()->json($checkupPrescriptionDone);
    }


    /**
     * _Create Patientcheckup_
     *
     * Patientcheckup store endpoint, User must have sufficient balance for doctor rate, returns patientcheckup instance. !! token required | patient
     *
     *
     * @bodyParam patient_id int required The patient id associated with call.
     * @bodyParam  doctor_id string required The doctor id associated with call.
     * @bodyParam  start_time string required The datetime indicating starting time of call. Example: "", "2020-07-10T14:19:24.000000Z"
     * @bodyParam  end_time string required The datetime indicating ending time of call. Can be set blank to indicate start of checkup. Example: "", "2020-07-10T14:40:30.000000Z"
     *
     *
     * @response  201 {
     * "patient_id": 1,
     * "doctor_id": 6,
     * "start_time": "2020-07-10T21:30:47.000000Z",
     * "end_time": null,
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
            'start_time' => 'required',
            'end_time' => 'present| nullable',
        ]);

        $patient = Patient::where('id', $request->patient_id)
            ->where('user_id', $this->user->id)->first();
        if (!$patient) {
            return response()->json('No patient selected associated with user', 400);
        }


        $doctor = Doctor::findOrFail($request->doctor_id);
        $checkupTransactionHandler = new CheckupTransactionHandler();

        $newPatientCheckup = $checkupTransactionHandler->createNewCheckup($patient, $doctor, (strlen($request->start_time) == 0) ? null : Carbon::parse($request->start_time), (strlen($request->end_time) == 0) ? null : Carbon::parse($request->end_time));
        if (!$newPatientCheckup) {
            return response()->json('Insufficient Balance', 400);
        }

        return response()->json($newPatientCheckup, 201);
    }


    private function createCheckupPrescription(Patientcheckup $patientcheckup){
        //create checkupprescription as patientcheckup endtime submitted(indicates end of checkup)
        $prescription = Checkupprescription::where('patientcheckup_id', $patientcheckup->id)->first();
        if (!$prescription) {
            $newCheckupPrescription = new Checkupprescription();
            $newCheckupPrescription->patientcheckup_id = $patientcheckup->id;
            $newCheckupPrescription->status = 0; //initialized(pending content)
            do {
                $code = Str::random(16);
                $checkupPrescription = Checkupprescription::where('code', $code)->first();
            } while ($checkupPrescription);
            $newCheckupPrescription->code = $code;
            $newCheckupPrescription->save();
        }
    }



    /**
     * _Update Checkup_
     *
     * Patientcheckup update patient and doctor ratings and endtime. !! token required | patient, doctor
     *
     *
     * @urlParam patientcheckup required The patientcheckup id.
     * @bodyParam start_time string required Call start time. Example: "2020-07-10T21:45:47.000000Z"
     * @bodyParam end_time string required Call end time. Example: "2020-07-10T21:45:47.000000Z"
     * @bodyParam doctor_tags json_array The doctor service tags.
     * @bodyParam  patient_tags json_array The patient behavior tags.
     *
     * @response  204 ""
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
            'start_time' => 'required',
            'end_time' => 'required',
            'patient_tags' => 'sometimes| array',
            'doctor_tags' => 'sometimes| array',
        ]);

        if ($this->user->hasRole('doctor')) {
            $patientcheckup->start_time = Carbon::parse($request->start_time);
            $patientcheckup->end_time = Carbon::parse($request->end_time);
            if ($request->has('patient_tags')) {
                $patientcheckup->patient_tags = json_encode($request->patient_tags);
            }

            $doctorappointment = Doctorappointment::where('patientcheckup_id', $patientcheckup->id)->first();
            if($doctorappointment){
                $doctorappointment->status = 1;
                $doctorappointment->save();
            }

        } else {
            if ($request->has('doctor_tags')) {
                $patientcheckup->doctor_tags = json_encode($request->doctor_tags);
            }
        }

        $patientcheckup->save();
        $pushNotificationHandler = new CheckupCallHandler();
        $pushNotificationHandler->terminateCallSession($patientcheckup);
        $pushNotificationHandler->checkDoctorSchedulesAndSetActiveStatus($patientcheckup->doctor);
        $this->createCheckupPrescription($patientcheckup);
        return response()->noContent();
    }

    /**
     * _Create Access Token_
     *
     * Create Patientcheckup joining information and update to firestore for call notification. !! token required | patient | doctor
     *
     * @bodyParam  patientcheckup_code string required The patientcheckup code for which the call room information is generated
     * @bodyParam is_patientcall bool required The boolean representation to detect from which end call is generated.
     *
     * @response  201 {
     * "access_token": "skadbi1212hdiu92basoicasic",
     * "room_name": "demo room",
     * "caller_name": "patient name/ doctor name",
     * "checkup_code": "asd1e012jf2f21f",
     * "time": "2020-07-11T09:46:43.000000Z"
     * }
     *
     *
     */
    public function sendCheckupCallNotification(Request $request)
    {
        $this->validate($request, [
            "patientcheckup_code" => "required",
            "is_patientcall" => "required| boolean"
        ]);
        $patientcheckup = Patientcheckup::where('code', $request->patientcheckup_code)->firstOrFail();
        $pushNotificationHandler = new CheckupCallHandler();
        $data = $pushNotificationHandler->createCallRequest($patientcheckup, !$request->is_patientcall);
        return response()->json($data, 201);
    }
}
