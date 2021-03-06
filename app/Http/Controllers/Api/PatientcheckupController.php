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
use App\User;
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


    public function getPatientCheckupsByUser(User $user)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') &&
            !$this->user->hasRole('admin:user') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }

        $userPatientIds = Patient::where('user_id', $user->id)->pluck('id');
        $checkupsByPatientIds = Patientcheckup::whereIn('patient_id', $userPatientIds)
            ->whereNotNull('start_time')
            ->pluck('id');

        $checkupPrescriptionDone = Checkupprescription::whereIn('patientcheckup_id', $checkupsByPatientIds)
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $checkupPrescriptionDone->getCollection()->transform(function ($checkupPrescription) {
            $checkup = $checkupPrescription->patientcheckup;
            $patient = $checkup->patient;
            unset($checkup->patient);
            unset($checkupPrescription->patientcheckup);
            return [
                "id" => $checkup->id,
                "start_time" => $checkup->start_time,
                "doctor" => $checkup->doctor,
                "amount" => $checkup->transaction->amount,
                "patientcheckup" => $checkup,
                "checkupprescription" => $checkupPrescription
            ];
        });
        return response()->json($checkupPrescriptionDone);
    }
    public function getPatientCheckupsByPatient(Patient $patient)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') &&
            !$this->user->hasRole('admin:user') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }


        $checkupsByPatientIds = Patientcheckup::where('patient_id', $patient->id)
            ->whereNotNull('start_time')
            ->pluck('id');

        $checkupPrescriptionDone = Checkupprescription::whereIn('patientcheckup_id', $checkupsByPatientIds)
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $checkupPrescriptionDone->getCollection()->transform(function ($checkupPrescription) {
            $checkup = $checkupPrescription->patientcheckup;
            $patient = $checkup->patient;
            unset($checkup->patient);
            unset($checkupPrescription->patientcheckup);
            return [
                "id" => $checkup->id,
                "start_time" => $checkup->start_time,
                "doctor" => $checkup->doctor,
                "amount" => $checkup->transaction->amount,
                "patientcheckup" => $checkup,
                "checkupprescription" => $checkupPrescription
            ];
        });
        return response()->json($checkupPrescriptionDone);
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
            ->orderBy('id', 'DESC')
            ->paginate(20);
        $checkupPrescriptionDone->getCollection()->transform(function ($checkupPrescription) {
            $checkup = $checkupPrescription->patientcheckup;
            $patient = $checkup->patient;
            unset($checkup->patient);
            unset($checkupPrescription->patientcheckup);
            return [
                "id" => $checkup->id,
                "start_time" => $checkup->start_time,
                "patient" => $patient,
                "amount" => $checkup->transaction->amount,
                "patientcheckup" => $checkup,
                "checkupprescription" => $checkupPrescription
            ];
        });
        return response()->json($checkupPrescriptionDone);
    }


    public function getMissedPatientCheckupsByDoctor(Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }

        $missedApointmentCheckupIds = Doctorappointment::where('doctor_id', $doctor->id)
            ->whereDate('end_time', Carbon::now())
            ->where('end_time', '<', Carbon::now())
            ->where('status', 0)
            ->pluck('patientcheckup_id');
        $missedCheckups = Patientcheckup::where('doctor_id', $doctor->id)
            ->whereDate('start_time', Carbon::now())
            ->whereNull('end_time')
            ->whereNotIn('id', $missedApointmentCheckupIds)
            ->orWhereIn('id', $missedApointmentCheckupIds)
            ->get();
        $missedCheckups->load('patient');
        return response()->json($missedCheckups);
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


    /**
     * _Update Checkup_
     *
     * Patientcheckup update patient and doctor ratings and endtime. !! token required | patient, doctor
     *
     *
     * @urlParam patientcheckup required The patientcheckup id.
     * @bodyParam start_time string required Call start time. Example: "2020-07-10T21:45:47.000000Z"
     * @bodyParam end_time string required Call end time. Example: "2020-07-10T21:45:47.000000Z"
     * @bodyParam status int required Call checkup status. 0=>ongoing, 1=>complete, 2=>incomplete, 3=>not received.
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
            'status' => 'required| numeric',
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
            if ($doctorappointment) {
                $doctorappointment->status = 1;
                $doctorappointment->save();
            }

            $pushNotificationHandler = new CheckupCallHandler();
            $pushNotificationHandler->terminateCallSession($patientcheckup);
            $pushNotificationHandler->checkDoctorSchedulesAndSetActiveStatus($patientcheckup->doctor);
        } else {
            if ($request->has('doctor_tags')) {
                $patientcheckup->doctor_tags = json_encode($request->doctor_tags);
            }
        }
        $patientcheckup->status = $request->status;
        $patientcheckup->save();

        return response()->noContent();
    }

    /**
     * _Update Patientcheckup status_
     *
     * Patientcheckup update call status, used if call not picked or ignored. !! token required | patient, doctor
     *
     *
     * @urlParam patientcheckup required The patientcheckup id.
     * @bodyParam status int required Call status. 0=>ongoing, 1=>complete, 2=>incomplete, 3=>not received
     *
     * @response  204 ""
     *
     *
     */
    public function endCallSession(Request $request, Patientcheckup $patientcheckup)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') &&
            !$this->user->hasRole('doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'status' => 'required| numeric',
        ]);

        $patientcheckup->status = $request->status;
        $pushNotificationHandler = new CheckupCallHandler();
        $pushNotificationHandler->terminateCallSession($patientcheckup);
        $pushNotificationHandler->checkDoctorSchedulesAndSetActiveStatus($patientcheckup->doctor);
        $patientcheckup->save();

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
