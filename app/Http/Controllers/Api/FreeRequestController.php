<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorappointment;
use App\Doctorschedule;
use App\Doctortype;
use App\Freerequest;
use App\Http\Controllers\Handlers\DoctorScheduleHandler;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\Patient;
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
class FreeRequestController extends Controller
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


    public function fetchRequestsBySchedule(Doctorschedule $doctorschedule)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:patientcheckup')) {
            return response()->json('Forbidden Access', 403);
        }
        $freeRequests = Freerequest::where('doctorschedule_id', $doctorschedule->id)
            ->paginate(50);
        $freeRequests->load('patient', 'patient.user');
        return response()->json($freeRequests);
    }

    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user') &&
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'patient_id' => 'required| numeric',
            'doctorschedule_id' => 'required| numeric',
//            'max_appointments_per_day' => 'required| numeric',
        ]);


        $patient = Patient::findOrFail($request->patient_id);
        $doctorSchedule = Doctorschedule::where('id', $request->doctorschedule_id)
            ->where('type', 2)
            ->firstOrFail();

        $existingRequest = Freerequest::where('patient_id', $patient->id)
            ->where('doctorschedule_id', $doctorSchedule->id)
            ->first();

        if($existingRequest){
            return response()->json(['message' => 'free request present for schedule'], 400);
        }

        $newFreeRequest = new Freerequest();
        $newFreeRequest->patient_id = $patient->id;
        $newFreeRequest->doctorschedule_id = $doctorSchedule->id;
        $newFreeRequest->save();
        return response()->json($newFreeRequest, 201);
    }

    public function delete(Doctorschedule $doctorschedule)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user')
        ) {
            return response()->json('Forbidden Access', 403);
        }

    }
}
