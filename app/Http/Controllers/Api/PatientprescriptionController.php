<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patientprescription;
use Illuminate\Http\Request;

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
