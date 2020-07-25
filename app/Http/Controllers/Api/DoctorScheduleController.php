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
     * ],
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
            'doctortype_id' => 'required| numeric',
            'name' => 'required',
            'bmdc_number' => 'required| unique:doctors',
            'rate' => 'required| numeric',
            'offer_rate' => 'sometimes| numeric',
            'gender' => 'required| numeric',
            'mobile' => 'required| unique:users| min: 11| max: 14',
            'email' => 'required',
            'workplace' => 'required',
            'designation' => 'required',
            'medical_college' => 'required',
            'postgrad' => 'present| nullable',
            'others_training' => 'present| nullable',
            'start_time' => 'sometimes| date_format:H:i',
            'end_time' => 'sometimes| date_format:H:i',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);
        $newDoctor = $this->createDoctor($request, false);
        return response()->json($newDoctor, 201);
    }

    /**
     * _Create Doctor by Admin_
     *
     * Doctor store endpoint used by admin, returns doctor instance. Doctor instance approved !! token required | super_admin, admin:doctor
     *
     * @bodyParam  doctortype_id int required The doctortype id.
     * @bodyParam  payment_style int required The payment process of doctor selected by admin. 0 => patient transaction, 1 => paid by organization
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
     * "user_id": 10,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000001",
     * "rate": 100,
     * "offer_rate": 100,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "medical_college": "dmc",
     * "others_training": "sdaosdmoaismdioasmdioas",
     * "postgrad": "dmc",
     * "updated_at": "2020-07-10T14:57:19.000000Z",
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "id": 4,
     * "activation_status": 1,
     * "payment_style": 1
     * }
     */
    public function createApprovedDoctor(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor')) {
            return response()->json('Forbidden Access', 403);
        }


        $this->validate($request, [
            'doctortype_id' => 'required| numeric',
            'payment_style' => 'required| numeric',
            'name' => 'required',
            'bmdc_number' => 'required| unique:doctors',
            'rate' => 'required| numeric',
            'offer_rate' => 'sometimes| numeric',
            'gender' => 'required| numeric',
            'mobile' => 'required| unique:users| min: 11| max: 14',
            'email' => 'required',
            'workplace' => 'required',
            'designation' => 'required',
            'medical_college' => 'required',
            'postgrad' => 'present| nullable',
            'others_training' => 'present| nullable',
            'start_time' => 'sometimes| date_format:H:i',
            'end_time' => 'sometimes| date_format:H:i',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);
        $newDoctor = $this->createDoctor($request, true);
        $newDoctor->activation_status = 1;
        $newDoctor->payment_style = $request->payment_style;
        $newDoctor->save();

        return response()->json($newDoctor, 201);
    }


}
