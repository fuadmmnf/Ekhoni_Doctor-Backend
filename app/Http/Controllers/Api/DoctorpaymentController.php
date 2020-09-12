<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Doctorpayments;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorpaymentController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }


    public function getDoctorPayments(Doctor $doctor)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor') &&
            !($this->user->hasRole('doctor') && $this->user->id == $doctor->user->id)
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $doctorPayments = Doctorpayments::where('doctor_id', $doctor->id)
            ->sortBy('date', 'DESC')
            ->paginate(10);
        return response()->json($doctorPayments);
    }

    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'doctor_id' => 'required| numeric',
            'amount' => 'required| numeric',
            'date' => 'required',
            'additional_info' => 'sometimes'
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        $newDoctorPayment = new Doctorpayments();
        $newDoctorPayment->doctor_id = $doctor->id;
        $newDoctorPayment->amount = $request->amount;
        $newDoctorPayment->date = Carbon::parse($request->date);
        if ($request->has('additional_info')) {
            $newDoctorPayment->additional_info = $request->additional_info;
        }
        $newDoctorPayment->save();

        $doctor->pending_amount = $doctor->pending_amount - $request->amount;
        $doctor->save();

        return response()->json($newDoctorPayment, 201);
    }
}
