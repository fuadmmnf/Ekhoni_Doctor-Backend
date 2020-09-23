<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorschedule;
use App\Doctortype;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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


    public function index()
    {
    }

    public function searchDoctor(Request $request)
    {
        $query = $request['searchquery'];
        $doctors = Doctor::select('doctors.*')
            ->where('activation_status', 1)
            ->where('doctors.name', 'LIKE', '%' . $query . '%')
            ->orWhere('doctors.bmdc_number', 'LIKE', '%' . $query . '%')
            ->orWhere('doctors.designation', 'LIKE', '%' . $query . '%')
            ->orWhere('doctors.workplace', 'LIKE', '%' . $query . '%')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->orWhere('users.mobile', 'LIKE', '%' . $query . '%')
            ->with('user')
            ->paginate(15);

        return response()->json($doctors);
    }


    /**
     * Fetch Paginated Active Doctors By Doctortype
     *
     * Fetch active doctors, paginated response of doctor instances.
     *
     * @urlParam  doctortype_id required The Doctortype ID of doctors.
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 1,
     * "user_id": 2,
     * "doctortype_id": 1,
     * "name": "fuad doctor",
     * "bmdc_number": "1111111",
     * "payment_style": 1,
     * "activation_status": 1,
     * "status": 1,
     * "is_featured": 0,
     * "pending_amount": 0,
     * "commission": 0.23,
     * "rate": 100,
     * "offer_rate": 80,
     * "followup_rate": 80,
     * "report_followup_rate": 50,
     * "gender": 0,
     * "email": "doctor1@ekhonidoctor.com",
     * "workplace": null,
     * "designation": null,
     * "postgrad": null,
     * "medical_college": "dmc",
     * "other_trainings": null,
     * "portfolio": null,
     * "image": null,
     * "created_at": "2020-09-12T07:27:09.000000Z",
     * "updated_at": "2020-09-12T10:22:08.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctortypes/0/doctors/active?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctortypes/0/doctors/active?page=1",
     * "links": [
     * {
     * "url": null,
     * "label": "Previous",
     * "active": false
     * },
     * {
     * "url": "http://127.0.0.1:8000/api/doctortypes/0/doctors/active?page=1",
     * "label": 1,
     * "active": true
     * },
     * {
     * "url": null,
     * "label": "Next",
     * "active": false
     * }
     * ],
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctortypes/0/doctors/active",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */
    public function getActiveDoctorsByDoctorType($doctortype_id)
    {
        $availableDoctorsByType = Doctor::query();
        $availableDoctorsByType->where('activation_status', 1)
            ->where('status', 1);
        if ($doctortype_id != 0) {
            $availableDoctorsByType->where('doctortype_id', $doctortype_id);
        }
        $availableDoctorsByType = $availableDoctorsByType->paginate(10);
        return response()->json($availableDoctorsByType);
    }


    /**
     * Fetch Paginated Doctors Currently Scheduled with free slots
     *
     * Fetch free slotted scheduled doctors, paginated response of doctor instances.
     *
     * @urlParam  doctortype_id required The Doctortype ID of doctors.
     *
     * @response  200 {
     * "current_page": 1,
     * "data": [
     * {
     * "id": 1,
     * "user_id": 2,
     * "doctortype_id": 1,
     * "name": "fuad doctor",
     * "bmdc_number": "1111111",
     * "payment_style": 1,
     * "activation_status": 1,
     * "status": 1,
     * "is_featured": 0,
     * "pending_amount": 0,
     * "commission": 0.23,
     * "rate": 100,
     * "offer_rate": 80,
     * "followup_rate": 80,
     * "report_followup_rate": 50,
     * "gender": 0,
     * "email": "doctor1@ekhonidoctor.com",
     * "workplace": null,
     * "designation": null,
     * "postgrad": null,
     * "medical_college": "dmc",
     * "other_trainings": null,
     * "portfolio": null,
     * "image": null,
     * "created_at": "2020-09-12T07:27:09.000000Z",
     * "updated_at": "2020-09-12T10:22:08.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctortypes/0/doctors/scheduleleft?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctortypes/0/doctors/scheduleleft?page=1",
     * "links": [
     * {
     * "url": null,
     * "label": "Previous",
     * "active": false
     * },
     * {
     * "url": "http://127.0.0.1:8000/api/doctortypes/0/doctors/scheduleleft?page=1",
     * "label": 1,
     * "active": true
     * },
     * {
     * "url": null,
     * "label": "Next",
     * "active": false
     * }
     * ],
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctortypes/0/doctors/scheduleleft",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 1,
     * "total": 1
     * }
     */
    public function getAvailableScheduleDoctorsWithSlots($doctortype_id)
    {
        $doctorIds = Doctorschedule::where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->where('slots_left', '>', 0)
            ->pluck('doctor_id');

        $availableScheduleDoctors = Doctor::query();
        $availableScheduleDoctors->whereIn('id', $doctorIds)
            ->where('activation_status', 1);
        if ($doctortype_id != 0) {
            $availableScheduleDoctors->where('doctortype_id', $doctortype_id);
        }

        $availableScheduleDoctors = $availableScheduleDoctors->paginate(10);
        return response()->json($availableScheduleDoctors);
    }


    /**
     * Fetch Paginated Approved Doctors By Doctortype
     *
     * Fetch approved doctors, paginated response of doctor instances by doctortype.
     *
     * @urlParam  doctortype required The Doctortype ID of doctors.
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
     * "is_featured": 0,
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
     * "other_trainings": "sdaosdmoaismdioasmdioas",
     * "portfolio": "adqdi1fi1n "
     * "device_ids": null,
     * "booking_start_time": null,
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "updated_at": "2020-07-10T14:57:19.000000Z"
     * }
     * ],
     * "first_page_url": "http://127.0.0.1:8000/api/doctortypes/{doctortype}/doctors/approved?page=1",
     * "from": 1,
     * "last_page": 1,
     * "last_page_url": "http://127.0.0.1:8000/api/doctortypes/{doctortype}/doctors/approved?page=1",
     * "next_page_url": null,
     * "path": "http://127.0.0.1:8000/api/doctortypes/{doctortype}/doctors/approved",
     * "per_page": 10,
     * "prev_page_url": null,
     * "to": 2,
     * "total": 2
     * }
     */
    public function getAllApprovedDoctorsByDoctortype(Doctortype $doctortype)
    {
        $approvedDoctors = Doctor::where('doctortype_id', $doctortype->id)
            ->where('activation_status', 1)->paginate(10);
        return response()->json($approvedDoctors);
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
     * "is_featured": 0,
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
     * "other_trainings": "sdaosdmoaismdioasmdioas",
     * "portfolio": "adqdi1fi1n "
     * "device_ids": null,
     * "booking_start_time": null,
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "updated_at": "2020-07-10T14:57:19.000000Z"
     * }
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
        $approvedDoctors = Doctor::where('activation_status', 1)->with('user', 'doctortype')->paginate(10);
        return response()->json($approvedDoctors);
    }


    /**
     * Fetch All Featured Doctors
     *
     * Fetch featured doctors, response of doctor instances.
     *
     *
     * @response  200 [
     * {
     * "id": 4,
     * "user_id": 10,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000001",
     * "payment_style": 1,
     * "activation_status": 1,
     * "status": 0,
     * "is_featured": 1,
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
     * "other_trainings": "sdaosdmoaismdioasmdioas",
     * "portfolio": "adqdi1fi1n "
     * "device_ids": null,
     * "booking_start_time": null,
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "updated_at": "2020-07-10T14:57:19.000000Z"
     * }
     * ]
     */
    public function getAllFeaturedDoctors()
    {
        $featuredDoctors = Doctor::select()
            ->where('activation_status', 1)
            ->where('is_featured', 1)->get();
        $featuredDoctors->makeHidden(['commission', 'balance', 'pending_amount', 'mobile', 'email', 'bmdc_number', 'payment_style']);

        return response()->json($featuredDoctors);
    }


    /**
     * _Fetch Paginated Doctors Requests_
     *
     * Fetch pending doctor joining requests, paginated response of doctor instances. !! token required| super_admin, admin:doctor
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
     * "is_featured": 0,
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
     * "other_trainings": "sdaosdmoaismdioasmdioas",
     * "portfolio": "adqdi1fi1n "
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
            ->with('user')
            ->paginate(10);

        return response()->json($pendingDoctorRequests);
    }


    private function createDoctor(Request $doctorRequest, $isApproved): Doctor
    {
        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($doctorRequest->mobile, $doctorRequest->has('device_id') ? $doctorRequest->device_id : "");

        if ($isApproved) {
            $user->assignRole('doctor');
        }

        $doctorType = Doctortype::findOrFail($doctorRequest->doctortype_id);

        $newDoctor = new Doctor();
        $newDoctor->user_id = $user->id;
        $newDoctor->doctortype_id = $doctorType->id;
        $newDoctor->name = $doctorRequest->name;
        $newDoctor->bmdc_number = $doctorRequest->bmdc_number;
        $newDoctor->gender = $doctorRequest->gender;
        $newDoctor->email = $doctorRequest->email;
        $newDoctor->workplace = $doctorRequest->workplace;
        $newDoctor->designation = $doctorRequest->designation;
        $newDoctor->medical_college = json_encode($doctorRequest->medical_college);
        if ($doctorRequest->has('rate')) {
            $newDoctor->rate = $doctorRequest->rate;
            $newDoctor->offer_rate = ($doctorRequest->has('offer_rate')) ? $doctorRequest->offer_rate : $doctorRequest->rate;
            $newDoctor->followup_rate = ($doctorRequest->has('followup_rate')) ? $doctorRequest->followup_rate : $doctorRequest->offer_rate;
            $newDoctor->report_followup_rate = ($doctorRequest->has('report_followup_rate')) ? $doctorRequest->report_followup_rate : null;
        }

        $newDoctor->postgrad = $doctorRequest->postgrad;
        $newDoctor->other_trainings = $doctorRequest->other_trainings;
        $newDoctor->portfolio = $doctorRequest->portfolio;
        $newDoctor->save();
        unset($user->token);
        $user->password = Hash::make(($isApproved) ? $newDoctor->mobile . $newDoctor->code : $doctorRequest->password);
        $user->save();

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
     * @bodyParam  followup_rate int The rate of doctor if patient calls more than once. If not present it will be set to offer rate.
     * @bodyParam  first_appointment_rate int The initial appointment rate of doctor per patient. If not present it will be set to offer rate.
     * @bodyParam  report_followup_rate int The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
     * @bodyParam  gender int required The gender of doctor. 0 => male, 1 => female
     * @bodyParam  mobile string required The mobile of doctor. Must be unique across users table.
     * @bodyParam  email string required The mail address of doctor.
     * @bodyParam  workplace string required The workplace of doctor.
     * @bodyParam  designation string required The designation of doctor.
     * @bodyParam  medical_college string required The graduation college of doctor.
     * @bodyParam  postgrad string required Post Grad degree of doctor [can be blank].
     * @bodyParam  other_trainings string required Other degrees of doctor [can be blank].
     * @bodyParam  portfolio string required Doctor details & achievements [can be blank].
     * @bodyParam  device_id string Phone device id for FCM.
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
     * "other_trainings": "sdaosdmoaismdioasmdioas",
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
            'rate' => 'sometimes| numeric',
            'offer_rate' => 'sometimes| numeric',
            'followup_rate' => 'sometimes| numeric',
            'report_followup_rate' => 'sometimes| numeric',
            'gender' => 'required| numeric',
            'mobile' => 'required| unique:users| min: 11| max: 14',
            'email' => 'required',
            'password' => 'required| min: 6| confirmed',
            'workplace' => 'required',
            'designation' => 'present',
            'medical_college' => 'required',
            'postgrad' => 'present| nullable',
            'other_trainings' => 'present| nullable',
            'portfolio' => 'present| nullable',
            'device_id' => 'sometimes'
        ]);
        Doctortype::findOrFail($request->doctortype_id);
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
     * @bodyParam  commission double required The commission percentage of doctor per call/appointment.
     * @bodyParam  rate int required The usual rate of doctor per call/appointment.
     * @bodyParam  offer_rate int The discounted rate of doctor per call/appointment. If not present it will be set to usual rate.
     * @bodyParam  report_followup_rate int The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
     * @bodyParam  gender int required The gender of doctor. 0 => male, 1 => female
     * @bodyParam  mobile string required The mobile of doctor. Must be unique across users table.
     * @bodyParam  email string required The mail address of doctor.
     * @bodyParam  workplace string required The workplace of doctor.
     * @bodyParam  designation string required The designation of doctor.
     * @bodyParam  medical_college string required The graduation college of doctor.
     * @bodyParam  postgrad string required Post Grad degree of doctor [can be blank].
     * @bodyParam  other_trainings string required Other degrees of doctor [can be blank].
     * @bodyParam  portfolio string required Doctor details & achievements [can be blank].
     *
     * @response  201 {
     * "user_id": 10,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000001",
     * "rate": 100,
     * "offer_rate": 100,
     * "report_followup_rate": 100,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "medical_college": "dmc",
     * "other_trainings": "sdaosdmoaismdioasmdioas",
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
            'commission' => 'required| numeric',
            'rate' => 'required| numeric',
            'offer_rate' => 'sometimes| numeric',
            'followup_rate' => 'sometimes| numeric',
            'report_followup_rate' => 'sometimes| numeric',
            'gender' => 'required| numeric',
            'mobile' => 'required| unique:users| min: 11| max: 14',
            'email' => 'required',
            'workplace' => 'required',
            'designation' => 'present',
            'medical_college' => 'required',
            'postgrad' => 'present| nullable',
            'other_trainings' => 'present| nullable',
            'portfolio' => 'present| nullable',
        ]);
        Doctortype::findOrFail($request->doctortype_id);
        $newDoctor = $this->createDoctor($request, true);
        $newDoctor->activation_status = 1;
        $newDoctor->commission = $request->commission;
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
     * @bodyParam  commission double required The commission percentage of doctor per call/appointment.
     * @bodyParam  rate int required The usual rate of doctor per call/appointment.
     * @bodyParam  offer_rate int required The discounted rate of doctor per call/appointment. If not present it will be set to usual rate.
     * @bodyParam  followup_rate int required The rate of doctor if patient calls more than once. If not present it will be set to offer rate.
     * @bodyParam  report_followup_rate int required The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
     * @response  204 ""
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
            'commission' => 'required| numeric',
            'rate' => 'required| numeric',
            'offer_rate' => 'required| numeric',
            'followup_rate' => 'required| numeric',
            'report_followup_rate' => 'required| numeric'
        ]);

        $doctor->activation_status = $request->activation_status;
        if ($request->activation_status == 1) {
            $doctor->user->assignRole('doctor');
            $doctor->commission = $request->commission;
            $doctor->rate = $request->rate;
            $doctor->offer_rate = $request->offer_rate;
            $doctor->followup_rate = $request->followup_rate;
            $doctor->report_followup_rate = $request->report_followup_rate;
        } else {
            $this->user->tokens()->delete();
        }
        $doctor->save();

        return response()->noContent();
    }


    /**
     * _Change Doctor Active Status_
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
        $doctor = $this->user->doctor;
        $doctor->status = $request->status;
        $doctor->save();
        return response()->noContent();
    }


    /**
     * _Update Doctor_
     *
     * Update doctor attributes !! token required | super_admin, admin:doctor, doctor
     *
     *
     * @urlParam  doctor required The ID of the doctor.
     * @bodyParam  commission double required The commission percentage of doctor per call/appointment.
     * @bodyParam  rate int  The usual rate of doctor per call/appointment.
     * @bodyParam  offer_rate int The discounted rate of doctor per call/appointment. If not present it will be set to usual rate.
     * @bodyParam  followup_rate int The rate of doctor if patient calls more than once. If not present it will be set to offer rate.
     * @bodyParam  report_followup_rate int The rate of doctor appointment within a specific checkup period per patient. If not present it will be set to offer rate.
     * @bodyParam  workplace string  The workplace of doctor.
     * @bodyParam  designation string  The designation of doctor.
     * @bodyParam  other_trainings string  Other degrees of doctor [can be blank].
     * @bodyParam  portfolio string  Doctor achievements [can be blank].
     *
     *
     * @response  204 ""
     */
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
            'commission' => 'sometimes| numeric',
            'rate' => 'sometimes| numeric',
            'offer_rate' => 'sometimes| numeric',
            'followup_rate' => 'sometimes| numeric',
            'report_followup_rate' => 'sometimes| numeric',
            'workplace' => 'sometimes',
            'designation' => 'sometimes',
            'other_trainings' => 'sometimes',
            'portfolio' => 'sometimes',
        ]);

        if ($request->has('commission')) {
            $doctor->commission = $request->commission;
        }

        if ($request->has('rate')) {
            $doctor->rate = $request->rate;
            $doctor->offer_rate = $request->rate;
        }

        if ($request->has('offer_rate')) {
            $doctor->offer_rate = $request->offer_rate;
        }


        if ($request->has('followup_rate')) {
            $doctor->followup_rate = $request->followup_rate;
        }


        if ($request->has('report_followup_rate')) {
            $doctor->report_followup_rate = $request->report_followup_rate;
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
        if ($request->has('other_trainings')) {
            $doctor->other_trainings = $request->other_trainings;
        }

        if ($request->has('portfolio')) {
            $doctor->portfolio = $request->portfolio;
        }


        $doctor->save();

        return response()->noContent();
    }


    /**
     * _Change Doctor Image_
     *
     * Update doctor image (Multipart Request)!! token required | super_admin, admin:doctor, doctor
     *
     *
     * @urlParam  doctor required The ID of the doctor.
     * @bodyParam  image file required The doctor image file.
     *
     *
     * @response  200 "images/users/doctors/1902jid.jpg"
     */
    public function changeDoctorMonogram(Request $request, Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'image' => 'required| image',
        ]);
        $image_path = public_path($doctor->image);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $image = $request->file('image');
        $filename = $doctor->code . '_' . time() . '.' . $image->getClientOriginalExtension();
        $location = public_path('images/users/doctors/' . $filename);
        File::put($location, $image->get());
        $doctor->image = 'images/users/doctors/' . $filename;
        $doctor->save();
        return response()->json($doctor->image, 200);
    }

//
//    /**
//     * _Change Doctor Booking Status_
//     *
//     * Update doctor activation_status. !! token required| super_admin, admin:doctor
//     *
//     * @urlParam  doctor required The ID of doctor.
//     * @bodyParam booking_start_time string required The booking starting time for patient. 'date time string' => booking start time, 'blank string' => booking finished. Example: "2020-07-10T14:19:24.000000Z", ''
//     *
//     * @response  204 ""
//     * @response  400 "another user is currently setting appointment"
//     */
//    public function changeDoctorBookingStatus(Request $request, Doctor $doctor)
//    {
//        if (!$this->user ||
//            !$this->user->hasRole('patient') &&
//            !$this->user->hasRole('super_admin') &&
//            !$this->user->hasRole('admin:doctor')
//
//        ) {
//            return response()->json('Forbidden Access', 403);
//        }
//        $this->validate($request, [
//            'booking_start_time' => 'present| nullable',
//        ]);
//
//        $booking_time = Carbon::parse($request->booking_start_time);
//        if (strlen($request->booking_start_time) == 0) {
//            $doctor->booking_start_time = null;
//        } elseif ($booking_time->diffInMinutes($doctor->booking_start_time) > 30) {
//            $doctor->booking_start_time = $booking_time;
//        } else {
//            return response()->json('another user is currently setting appointment', 400);
//        }
//        $doctor->save();
//
//        return response()->noContent();
//    }
//

}
