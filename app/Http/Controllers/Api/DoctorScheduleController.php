<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorappointment;
use App\Doctorschedule;
use App\Doctortype;
use App\Http\Controllers\Handlers\AppointmentHandler;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * @group  Doctor Schedule management
 *
 * APIs related to Doctor
 */
class DoctorScheduleController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }


    /**
     * Fetch Paginated Doctor Schedules By Doctor
     *
     * Fetch doctor schedules starting from present date, response of doctorschedule instances.
     *
     * @urlParam  doctor required The Doctor ID of doctor schedules.
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 2,
     * "doctor_id": 1,
     * "start_time": "2020-07-26 18:30:00",
     * "end_time": "2020-07-26 18:30:00",
     * "max_appointments_per_day": 4,
     * "schedule_slots": "[{\"time\":\"2020-07-26T18:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-26T18:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-26T18:30:00.000000Z\",\"status\":0},{\"time\":\"2020-07-26T18:30:00.000000Z\",\"status\":0}]",
     * "created_at": "2020-07-25T20:44:16.000000Z",
     * "updated_at": "2020-07-25T20:44:16.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctors/1/doctorschedules?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctors/1/doctorschedules?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctors/1/doctorschedules",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */
    public function getDoctorSchedulesByDoctorFromPresentDate(Doctor $doctor)
    {
        $doctorSchedulesByDoctorFromPresentDate = Doctorschedule::where('doctor_id', $doctor->id)
            ->whereDate('start_time', '>=', Carbon::now())
            ->paginate(10);

        return response()->json($doctorSchedulesByDoctorFromPresentDate);
    }


    /**
     * Create Doctor
     *
     * Doctor store endpoint, returns doctor instance. Doctor instance not approved and payment style depends on customer transaction by default
     *
     * @bodyParam doctortype_id int required The doctortype id.
     * @bodyParam  name string required The fullname of doctor.
     * @bodyParam  bmdc_number string required The registered bmdc_number of doctor. Unique for doctors.
     * @bodyParam  rate int required The usual rate of doctor per call/appointment.
     * @bodyParam  offer_rate int The discounted rate of doctor per call/appointment. If not presen it will be set to usual rate.
     * @bodyParam  gender int required The gender of doctor. 0 => male, 1 => female
     * @bodyParam  mobile string required The mobile of doctor. Must be unique across users table.
     * @bodyParam  email string required The mail address of doctor.
     * @bodyParam  workplace string required The workplace of doctor.
     * @bodyParam  designation string required The designation of doctor.
     * @bodyParam  medical_college string required The graduation college of doctor.
     * @bodyParam  post_grad string required Post Grad degree of doctor [can be blank].
     * @bodyParam  others_training string required Other degrees of doctor [can be blank].
     * @bodyParam  start_time string Duty start time for specialist. Must maintain format. Example: "10:30"
     * @bodyParam  end_time string Duty end time for specialist. Must maintain format. Example: "3:30"
     * @bodyParam  max_appointments_per_day int  Max number of appointments each day in case of specialist within start-end time.
     *
     *
     * @response  201 {
     * "user_id": 8,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000000",
     * "rate": 100,
     * "offer_rate": 100,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "medical_college": "dmc",
     * "postgrad": "dmc",
     * "others_training": "sdaosdmoaismdioasmdioas",
     * "updated_at": "2020-07-10T14:19:24.000000Z",
     * "created_at": "2020-07-10T14:19:24.000000Z",
     * "id": 2
     * }
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required| numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'max_appointments_per_day' => 'required| numeric',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        $newDoctorSchedule = new Doctorschedule();
        $newDoctorSchedule->doctor_id = $doctor->id;
        $newDoctorSchedule->start_time = Carbon::parse($request->start_time);
        $newDoctorSchedule->end_time = Carbon::parse($request->end_time);
        $newDoctorSchedule->max_appointments_per_day = $request->max_appointments_per_day;

        $scheduleInterval = floor(($newDoctorSchedule->end_time->diffInMinutes($newDoctorSchedule->start_time)) / $newDoctorSchedule->max_appointments_per_day);
        $time = $newDoctorSchedule->start_time;
        $appointmentSchedules = array();

        while ($time < $newDoctorSchedule->end_time) {
            $appointmentSchedules[] = [
                "time" => $time->copy(),
                "status" => 0 // 0 available, 1 booked
            ];
            $time = $time->addMinutes($scheduleInterval);
        }
        $newDoctorSchedule->schedule_slots = json_encode($appointmentSchedules);

        $newDoctorSchedule->save();
        return response()->json($newDoctorSchedule, 201);
    }


}
