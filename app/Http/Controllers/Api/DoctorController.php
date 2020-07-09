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
use Illuminate\Support\Str;

class DoctorController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:super_admin|admin:doctor'])->only('evaluateDoctorJoiningRequest');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function getAvailableDoctorsByDoctorType(Doctortype $doctortype){
        $availableDoctorsByType = Doctor::where('doctortype_id', $doctortype->id)
            ->where('activation_status', 1)
            ->where('status', 0)->paginate(10);

        return response()->json($availableDoctorsByType);
    }

    public function getAllPendingDoctorRequest(){
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
            'start_time' => 'sometimes',
            'payment_style' => 'sometimes',
            'end_time' => 'sometimes',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);

        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($request->mobile);
        $user->assignRole('doctor');

        $doctorType = Doctortype::findOrFail($request->doctortype_id);

        $newDoctor = new Doctor();
        $newDoctor->user_id = $user->id;
        $newDoctor->doctortype_id = $doctorType->id;
        $newDoctor->name = $request->name;
        //check for admin permission in payment_style
        if($request->has('payment_style')) $newDoctor->payment_style = $request->payment_style;
        $newDoctor->bmdc_number = $request->bmdc_number;
        $newDoctor->rate = $request->rate;
        $newDoctor->offer_rate = ($request->has('offer_rate'))? $request->offer_rate: $request->rate;
        $newDoctor->gender = $request->gender;
        $newDoctor->email = $request->email;
        $newDoctor->workplace = $request->workplace;
        $newDoctor->designation = $request->designation;
        $newDoctor->medical_college = $request->medical_college;
        $newDoctor->others_training = $request->others_training;
        $newDoctor->starting_time = ($request->has('starting_time'))? Carbon::parse($request->starting_time): null;
        $newDoctor->end_time = ($request->has('end_time'))? Carbon::parse($request->end_time): null;
        $newDoctor->max_appointments_per_day = ($request->has('max_appointments_per_day'))? $request->max_appointments_per_day: null;
        $newDoctor->password = Hash::make($newDoctor->mobile. $newDoctor->code);
        $newDoctor->save();

        return response()->json($newDoctor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(Doctor $doctor)
    {
        //
    }


    public function evaluateDoctorJoiningRequest(Request $request, Doctor $doctor){
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
            'rate' => 'required| numeric',
            'offer_rate' => 'required| numeric',
            'workplace' => 'sometimes',
            'designation' => 'sometimes',
            'others_training' => 'sometimes',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'max_appointments_per_day' => 'sometimes| numeric',
        ]);
        $doctor->rate = $request->rate;
        $doctor->offer_rate = $request->offer_rate;

        if($request->has('payment_style')){
            $doctor->payment_style = $request->payment_style;
        }

        if($request->has('workplace')){
            $doctor->workplace = $request->workplace;
        }
        if($request->has('designation')){
            $doctor->designation = $request->designation;
        }
        if($request->has('others_training')){
            $doctor->others_training = $request->others_training;
        }
        if($request->has('starting_time') && $request->has('ending_time')){
            //cancel all appointments in between these times
            $appointmentHandler = new AppointmentHandler();
            $appointmentHandler->cancelAppointmentBetweenTimeRange($doctor, $request->start_time, $request->end_time);

            $doctor->start_time = Carbon::parse($request->start_time);
            $doctor->end_time = Carbon::parse($request->end_time);
        }
        if($request->has('max_appointments_per_day')) {
            $doctor->max_appointments_per_day = $request->max_appointments_per_day;
        }
        $doctor->save();

        return response()->noContent();
    }

    public function getLastDoctorBookingStartTime(Doctor $doctor){
        return response()->json($doctor->booking_start_time, 200);
    }

    public function changeDoctorBookingStatus(Request $request, Doctor $doctor){
        $this->validate($request, [
           'booking_start_time' => 'present| nullable',
        ]);
        $doctor->booking_start_time = ($request->booking_start_time == null)? null: Carbon::parse($request->booking_start_time);
        $doctor->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
