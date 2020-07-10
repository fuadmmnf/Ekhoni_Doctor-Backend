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
        $publicMethods = ['getAvailableDoctorsByDoctorType', 'store'];
        $adminMethods = ['createApprovedDoctor', 'getAllPendingDoctorRequest', 'evaluateDoctorJoiningRequest', 'update'];
        $patientMethods = ['changeDoctorBookingStatus'];
        $doctorMethods = ['update', 'changeActiveStatus'];
        $requestMethod = explode('@', Route::currentRouteAction())[1];

        $this->middleware('auth:sanctum')->except($publicMethods);
        if (!in_array($requestMethod, $publicMethods)) {
            $this->user = $request->user('sanctum');

            if ($this->user->hasRole('patient')) {
                $this->middleware('role:patient')->only($patientMethods);
            }
            if ($this->user->hasRole('doctor')) {
                $this->middleware('role:doctor')->only($doctorMethods);
            }
            if ($this->user->hasRole('super_admin') || $this->user->hasRole('admin:doctor')) {
                $this->middleware('role:super_admin|admin:doctor')->only($adminMethods);
            }
        }
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
     * Fetch Available doctors by doctortype
     *
     * Fetch doctor types list.
     *
     * @response  [
     * {
     * "id": 1,
     * "type": 0,
     * "specialization": "cardiology",
     * "created_at": "2020-07-10T10:09:17.000000Z",
     * "updated_at": "2020-07-10T10:09:17.000000Z"
     * }
     * ]
     */
    public function getAvailableDoctorsByDoctorType(Doctortype $doctortype)
    {
        $availableDoctorsByType = Doctor::where('doctortype_id', $doctortype->id)
            ->where('activation_status', 1)
            ->where('status', 1)->paginate(10);

        return response()->json($availableDoctorsByType);
    }


    public function getAllApprovedDoctors()
    {
        $approvedDoctors = Doctor::where('activation_status', 1)->paginate(10);
        return response()->json($approvedDoctors);
    }

    public function getAllPendingDoctorRequest()
    {
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
            'others_training' => 'present',
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
     * "updated_at": "2020-07-10T14:57:19.000000Z",
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "id": 4,
     * "activation_status": 1,
     * "payment_style": 1
     * }
     */
    public function createApprovedDoctor(Request $request)
    {
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
            'others_training' => 'required',
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
     * Display the specified resource.
     *
     * @param \App\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }


    public function evaluateDoctorJoiningRequest(Request $request, Doctor $doctor)
    {
        $this->validate($request, [
            'activation_status' => 'required| integer| between: 0,1',
        ]);

        $doctor->activation_status = $request->activation_status;
        $doctor->save();

        return response()->noContent();
    }

    public function changeActiveStatus(Request $request, Doctor $doctor)
    {
        $this->validate($request, [
            'status' => 'required| numeric',
        ]);
        $doctor->status = $request->status;
        $doctor->save();
        return response()->noContent();
    }

    public function update(Request $request, Doctor $doctor)
    {
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

    public function changeDoctorBookingStatus(Request $request, Doctor $doctor)
    {
        $this->validate($request, [
            'booking_start_time' => 'present| nullable',
        ]);

        $booking_time = Carbon::parse($request->booking_start_time);
        if ($request->booking_start_time == null || $booking_time->diffInMinutes($doctor->booking_start_time) > 30) {
            $doctor->booking_start_time = $booking_time;
            $doctor->save();
        } else {
            return response()->json('another user is currently setting appointment', 400);
        }

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
