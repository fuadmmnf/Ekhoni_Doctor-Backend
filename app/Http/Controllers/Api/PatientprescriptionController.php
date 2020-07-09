<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use App\Patientprescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class PatientprescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
           'patient_id' => 'required| numeric',
            'prescription' => 'required',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        $newPatientPrescription = new Patientprescription();
        $newPatientPrescription->patient_id = $patient->id;
        do
        {
            $code = Str::random(16);
            $patientprescription = Patientprescription::where('code', $code)->first();
        }
        while($patientprescription);

        if ($request->prescription) {
            // $filename = time(). '.' . explode('/', explode(':', substr($request->monogram, 0, strpos($request->monogram, ':')))[1])[0];
            $filename = $newPatientPrescription->code . '_' . time() . '.' . explode(';', explode('/', $request->prescription)[1])[0];
            $location = '/assets/images/patients/prescriptions/' . $filename;
            $file = Image::make($request->monogram)->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($location, $file);
            $newPatientPrescription->prescription_path = $location;
            $newPatientPrescription->save();

            return response()->json($newPatientPrescription, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patientprescription  $patientprescription
     * @return \Illuminate\Http\Response
     */
    public function show(Patientprescription $patientprescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patientprescription  $patientprescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Patientprescription $patientprescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patientprescription  $patientprescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patientprescription $patientprescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patientprescription  $patientprescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patientprescription $patientprescription)
    {
        //
    }
}
