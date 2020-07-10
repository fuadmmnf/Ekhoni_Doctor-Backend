<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorappointment;
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
 * @group  Doctor management
 *
 * APIs related to Doctor
 */
class DoctorController extends Controller
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
     * Fetch Paginated Active Doctors By Doctortype
     *
     * Fetch active doctors, paginated response of doctor instances.
     *
     * @urlParam  doctortype required The Doctortype ID of doctors.
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 6,
     * "user_id": 12,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000002",
     * "payment_style": 1,
     * "activation_status": 1,
     * "status": 1,
     * "rate": 100,
     * "offer_rate": 100,
     * "start_time": null,
     * "end_time": null,
     * "max_appointments_per_day": null,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "postgrad": "dmc",
     * "medical_college": "dmc",
     * "others_training": "sdaosdmoaismdioasmdioas",
     * "device_ids": null,
     * "booking_start_time": null,
     * "created_at": "2020-07-10T15:49:23.000000Z",
     * "updated_at": "2020-07-10T16:03:21.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctortypes/2/doctors/active?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctortypes/2/doctors/active?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctortypes/2/doctors/active",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */
    public function getActiveDoctorsByDoctorType(Doctortype $doctortype)
    {
        $availableDoctorsByType = Doctor::where('doctortype_id', $doctortype->id)
            ->where('activation_status', 1)
            ->where('status', 1)->paginate(10);

        return response()->json($availableDoctorsByType);
    }


    /**
     * Fetch Paginated Approved Doctors
     *
     * Fetch approved doctors, paginated response of doctor instances.
     *
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 4,
     * "user_id": 10,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000001",
     * "payment_style": 1,
     * "activation_status": 1,
     * "status": 0,
     * "rate": 100,
     * "offer_rate": 100,
     * "start_time": null,
     * "end_time": null,
     * "max_appointments_per_day": null,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "postgrad": "dmc",
     * "medical_college": "dmc",
     * "others_training": "sdaosdmoaismdioasmdioas",
     * "device_ids": null,
     * "booking_start_time": null,
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "updated_at": "2020-07-10T14:57:19.000000Z"
     * },
     * ...
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctors/approved?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctors/approved?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctors/approved",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 2,
     * "total": 2
     * }
     */
    public function getAllApprovedDoctors()
    {
        $approvedDoctors = Doctor::where('activation_status', 1)->paginate(10);
        return response()->json($approvedDoctors);
    }


    /**
     * Fetch Paginated Doctors Requests
     *
     * Fetch pending doctor joining requests, paginated response of doctor instances.
     *
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 2,
     * "user_id": 8,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000000",
     * "payment_style": 0,
     * "activation_status": 0,
     * "status": 0,
     * "rate": 100,
     * "offer_rate": 100,
     * "start_time": null,
     * "end_time": null,
     * "max_appointments_per_day": null,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "postgrad": "dmc",
     * "medical_college": "dmc",
     * "others_training": "sdaosdmoaismdioasmdioas",
     * "device_ids": null,
     * "booking_start_time": null,
     * "created_at": "2020-07-10T14:19:24.000000Z",
     * "updated_at": "2020-07-10T14:19:24.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctors/pending?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctors/pending?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctors/pending",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */

    public function getAllPendingDoctorRequest()
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor')) {
            return response()->json('Forbidden Access', 403);
        }

        $pendingDoctorRequests = Doctor::where('activation_status', 0)
            ->paginate(10);

        return response()->json($pendingDoctorRequests);
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


    private function createDoctor(Request $doctorRequest): Doctor
    {
        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($doctorRequest->mobile);
        $user->assignRole('doctor');

        $doctorType = Doctortype::findOrFail($doctorRequest->doctortype_id);

        $newDoctor = new Doctor();
        $newDoctor->user_id = $user->id;
        $newDoctor->doctortype_id = $doctorType->id;
        $newDoctor->name = $doctorRequest->name;
        $newDoctor->bmdc_number = $doctorRequest->bmdc_number;
        $newDoctor->rate = $doctorRequest->rate;
        $newDoctor->offer_rate = ($doctorRequest->has('offer_rate')) ? $doctorRequest->offer_rate : $doctorRequest->rate;
        $newDoctor->gender = $doctorRequest->gender;
        $newDoctor->email = $doctorRequest->email;
        $newDoctor->workplace = $doctorRequest->workplace;
        $newDoctor->designation = $doctorRequest->designation;
        $newDoctor->medical_college = $doctorRequest->medical_college;
        $newDoctor->offer_rate = ($doctorRequest->has('offer_rate')) ? $doctorRequest->offer_rate : $doctorRequest->rate;
        $newDoctor->postgrad = $doctorRequest->postgrad;
        $newDoctor->others_training = $doctorRequest->others_training;

        if ($doctorRequest->has('start_time') &&
            $doctorRequest->has('end_time') &&
            $doctorRequest->has('max_appointments_per_day')) {

            $newDoctor->start_time = strtotime($doctorRequest->start_time);
            $newDoctor->end_time = strtotime($doctorRequest->end_time);
            $newDoctor->max_appointments_per_day = ($doctorRequest->has('max_appointments_per_day')) ? $doctorRequest->max_appointments_per_day : null;
        }
        $newDoctor->password = Hash::make($newDoctor->mobile . $newDoctor->code);
        $newDoctor->save();
        return $newDoctor;
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
        $newDoctor = $this->createDoctor($request);
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
        $newDoctor = $this->createDoctor($request);
        $newDoctor->activation_status = 1;
        $newDoctor->payment_style = $request->payment_style;
        $newDoctor->save();

        return response()->json($newDoctor, 201);
    }


    /**
     * _Approve Doctor By Admin_
     *
     * Update doctor activation_status. !! token required| super_admin, admin:doctor
     *
     * @urlParam  doctor required The ID of doctor.
     * @bodyParam activation_status int required The activation indicatior. 0 => not approved, 1 => approved
     *
     * @response  204
     */
    public function evaluateDoctorJoiningRequest(Request $request, Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'activation_status' => 'required| integer| between: 0,1',
        ]);

        $doctor->activation_status = $request->activation_status;
        $doctor->save();

        return response()->noContent();
    }


    /**
     * _Create Doctor Active Status_
     *
     * Doctor update active status endpoint used by doctor.!! token required | doctor
     *
     * @bodyParam  status int required The doctor active status. 0 => inactive, 1 => active
     *
     *
     * @response  204 ""
     */
    public function changeActiveStatus(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'status' => 'required| numeric',
        ]);
        $doctor = $request->user('sanctum')->doctor;
        $doctor->status = $request->status;
        $doctor->save();
        return response()->noContent();
    }


    //needs testing after setting doctor appointment
    public function update(Request $request, Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'rate' => 'sometimes| numeric',
            'offer_rate' => 'sometimes| numeric',
            'workplace' => 'sometimes',
            'designation' => 'sometimes',
            'others_training' => 'sometimes',
            'start_time' => 'sometimes| date_format:H:i',
            'end_time' => 'sometimes| date_format:H:i',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);

        if ($request->has('rate')) {
            $doctor->rate = $request->rate;
            $doctor->offer_rate = $request->rate;
        }

        if ($request->has('offer_rate')) {
            $doctor->offer_rate = $request->offer_rate;
        }

        if ($request->has('payment_style')) {
            $doctor->payment_style = $request->payment_style;
        }

        if ($request->has('workplace')) {
            $doctor->workplace = $request->workplace;
        }
        if ($request->has('designation')) {
            $doctor->designation = $request->designation;
        }
        if ($request->has('others_training')) {
            $doctor->others_training = $request->others_training;
        }
        if ($request->has('starting_time') && $request->has('ending_time')) {
            //cancel all appointments in between these times
            $appointmentHandler = new AppointmentHandler();
//            $appointmentHandler->cancelAppointmentBetweenTimeRange($doctor, $request->start_time, $request->end_time);
            $doctorHasActiveAppointments = $appointmentHandler->checkIfDoctorHasRemainingAppointments($doctor);
            if ($doctorHasActiveAppointments) {
                return response()->json('must cancel active appointments first', 400);
            }

            if ($request->has('start_time') &&
                $request->has('end_time') &&
                $request->has('max_appointments_per_day')) {
                $doctor->start_time = strtotime($request->start_time);
                $doctor->end_time = strtotime($request->end_time);
                $doctor->max_appointments_per_day = $request->max_appointments_per_day;
            }

        }

        $doctor->save();

        return response()->noContent();
    }


    /**
     * _Change Doctor Booking Statu_
     *
     * Update doctor activation_status. !! token required| super_admin, admin:doctor
     *
     * @urlParam  doctor required The ID of doctor.
     * @bodyParam booking_start_time string required The booking starting time for patient. 'date time string' => booking start time, 'blank string' => booking finished. Example: "2020-07-10T14:19:24.000000Z", ''
     *
     * @response  204
     * @response  400 "another user is currently setting appointment"
     */
    public function changeDoctorBookingStatus(Request $request, Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient') &&
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor')

        ) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'booking_start_time' => 'present| nullable',
        ]);

        $booking_time = Carbon::parse($request->booking_start_time);
        if (strlen($request->booking_start_time) == 0) {
            $doctor->booking_start_time = null;
        } elseif ($booking_time->diffInMinutes($doctor->booking_start_time) > 30) {
            $doctor->booking_start_time = $booking_time;
        } else {
            return response()->json('another user is currently setting appointment', 400);
        }
        $doctor->save();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
