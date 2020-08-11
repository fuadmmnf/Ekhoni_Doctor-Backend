<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorappointment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Handlers\Checkup\CheckupCallHandler;
use App\Http\Controllers\Handlers\DoctorScheduleHandler;
use App\Http\Controllers\Handlers\CheckupTransactionHandler;
use App\Patient;
use App\Patientcheckup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @group  DoctorAppointment management
 *
 * APIs related to Scheduled Doctor Appointments
 */
class DoctorappointmentController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }

    /**
     * _Fetch Paginated Doctors Appointments by Status_
     *
     * Fetch scheduled doctor appointments, paginated response of doctorappointment instances. !! token required| super_admin, admin, doctor
     *
     * @urlParam doctor required The doctor id associated with appointments.
     * @urlParam  status required The status to query for the scheduled appointments. 0 => active, 1 => canceled, 2 => completed.
     *
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 1,
     * "doctor_id": 6,
     * "patientcheckup_id": 2,
     * "code": "fyFDiwwuVU2pzlO8",
     * "status": 0,
     * "start_time": "2020-07-14 14:19:24",
     * "end_time": "2020-07-14 14:40:24",
     * "created_at": "2020-07-11T11:51:21.000000Z",
     * "updated_at": "2020-07-11T12:18:16.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctors/6/doctorappointments/0?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctors/6/doctorappointments/0?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctors/6/doctorappointments/0",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */
    public function getAllDoctorAppointmentsByStatus(Doctor $doctor, $status)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }

        $paginatedDoctorAppointmentsByStatus = Doctorappointment::where('doctor_id', $doctor->id)
            ->orderBy('start_time', 'ASC')
            ->where('status', $status)->paginate(10);

        return response()->json($paginatedDoctorAppointmentsByStatus);
    }

    /**
     * Fetch Active Doctors Appointments By Date_
     *
     * Fetch scheduled valid doctor appointments by Date.
     *
     * @urlParam doctor required The doctor id associated with appointments.
     * @urlParam date required The query date.
     *
     *
     * @response  200 [
     * {
     * "id": 1,
     * "doctor_id": 6,
     * "patientcheckup_id": 2,
     * "code": "fyFDiwwuVU2pzlO8",
     * "status": 0,
     * "start_time": "2020-07-11 14:19:24",
     * "end_time": "2020-07-11 14:40:24",
     * "created_at": "2020-07-11T11:51:21.000000Z",
     * "updated_at": "2020-07-11T12:18:16.000000Z"
     * }
     * ]
     */
    public function getAllActiveDoctorAppointmentsByDate(Doctor $doctor, $date)
    {
        $date = Carbon::parse($date);
        $doctorAppointmentsByDate = Doctorappointment::where('doctor_id', $doctor->id)
            ->whereDate('start_time', '=', $date)
            ->orderBy('start_time', 'ASC')
            ->get();
        return response()->json($doctorAppointmentsByDate);
    }

    /**
     * _Fetch Paginated Upcoming Doctors Appointments_
     *
     * Fetch scheduled upcoming doctor appointments starting from current date, paginated response of doctorappointment instances. !! token required| super_admin, admin, doctor
     *
     * @urlParam doctor required The doctor id associated with appointments.
     *
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 1,
     * "doctor_id": 6,
     * "patientcheckup_id": 2,
     * "code": "fyFDiwwuVU2pzlO8",
     * "status": 0,
     * "start_time": "2020-07-14 14:19:24",
     * "end_time": "2020-07-14 14:40:24",
     * "patientcheckup": {
     * "patient_id": 1,
     * "doctor_id": 6,
     * "start_time": "null",
     * "end_time": null,
     * "code": "UenaBBVXuQF2F7A4",
     * "updated_at": "2020-07-11T09:46:43.000000Z",
     * "created_at": "2020-07-11T09:46:43.000000Z",
     * "id": 1
     * }
     * "created_at": "2020-07-11T11:51:21.000000Z",
     * "updated_at": "2020-07-11T12:18:16.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctors/6/doctorappointments/upcoming?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctors/6/doctorappointments/upcoming?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctors/6/doctorappointments/upcoming",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */
    public function getUpcomingDoctorAppointments(Doctor $doctor)
    {
        $upcomingDoctorAppointments = Doctorappointment::where('doctor_id', $doctor->id)
            ->whereDate('start_time', '>=', Carbon::now())
            ->orderBy('start_time', 'ASC')
            ->paginate(15);

        $upcomingDoctorAppointments->load('patientcheckup');
//        $upcomingDoctorAppointments = $upcomingDoctorAppointments->map(function ($appointment) {
//            $appointment->patientcheckup;
//            return $appointment;
//        })->values();
//        $upcomingDoctorAppointments->map(function ($doctorappointment) {
//            $patientcheckup = Patientcheckup::findOrFail($doctorappointment->patientcheckup_id);
//            $doctorappointment->patient = $patientcheckup->patient;
//            return $doctorappointment;
//        });

        return response()->json($upcomingDoctorAppointments);
    }

    /**
     * _Create Doctorappointment_
     *
     * Doctorappointment store endpoint, User must have sufficient balance for doctor rate, must maintain start_time for one of schedule_slots of doctorschedules(changes status of appointment slot to booked), returns doctorappointment instance. !! token required | patient
     *
     *
     * @bodyParam patient_id int required The patient id associated with call.
     * @bodyParam  doctor_id string required The doctor id associated with call.
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
            'patient_id' => 'required| numeric',
            'doctor_id' => 'required| numeric',
            'start_time' => 'required',
            'end_time' => 'required',
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
//        $doctor = Doctor::findOrFail($request->doctor_id);
        $transaction = $newPatientCheckup->transaction;


        if ($this->user->id != $patient->user->id) {
            return response()->json('User associated with token does not have patient associated with checkup', 400);
        }
//        if ($request->doctor_id != $patientCheckup->doctor->id) {
//            return response()->json('doctor_id and patientCheckup doctor id mismatch', 400);
//        }

        $newDoctorAppointment = new Doctorappointment();
        $newDoctorAppointment->doctor_id = $doctor->id;
        $newDoctorAppointment->patientcheckup_id = $newPatientCheckup->id;
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

        $appointmentHandler = new DoctorScheduleHandler();
        $appointmentHandler->setAppointmentInDoctorSchedule($newDoctorAppointment);


        return response()->json($newDoctorAppointment, 201);
    }


    /**
     * _Update Doctorappointment_
     *
     * Doctorappointment update, change appointment status. !! token required | doctor
     *
     *
     * @urlParam doctorappointment required The appointment id.
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


    public function sendAppointmentCheckupCallNotification(Doctorappointment $doctorappointment)
    {
        $pushNotificationHandler = new CheckupCallHandler();
        $patientcheckup = $doctorappointment->patientcheckup;
        $pushNotificationHandler->createCallRequest($patientcheckup->doctor, $patientcheckup->patient, true);
    }
}
