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
        $adminMethods = ['getAllPendingDoctorRequest', 'evaluateDoctorJoiningRequest', 'update'];
        $patientMethods = ['changeDoctorBookingStatus'];
        $doctorMethods = ['update'];
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
            ->where('status', 0)->paginate(10);

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


    private function createDoctor(Request $doctorRequest)
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
     * _Create Doctor_
     *
     * Doctor store endpoint, returns doctor instance. !! token required | super_admin, admin:doctor
     *
     *
     * @bodyParam type int required The type indication of doctor. Example: 0 => emergency, 1 => specialist
     * @bodyParam  specialization string required The main field of expertise. Example: "cardiology"
     *
     *
     * @response  201 {
     * "type": "1",
     * "specialization": "cardiology",
     * "updated_at": "2020-07-10T12:16:17.000000Z",
     * "created_at": "2020-07-10T12:16:17.000000Z",
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
            'mobile' => 'required| unique:doctors| min: 11| max: 14',
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
        return response()->json($newDoctor, 201);
    }


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
            'mobile' => 'required| unique:doctors| min: 11| max: 14',
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
        $newDoctor->activation_style = 1;
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


    public function update(Request $request, Doctor $doctor)
    {
        $this->validate($request, [
            'status' => 'required| numeric',
            'rate' => 'sometimes| numeric',
            'offer_rate' => 'sometimes| numeric',
            'workplace' => 'sometimes',
            'designation' => 'sometimes',
            'others_training' => 'sometimes',
            'start_time' => 'sometimes| date_format:H:i',
            'end_time' => 'sometimes| date_format:H:i',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);

        $doctor->status = $request->status;

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
