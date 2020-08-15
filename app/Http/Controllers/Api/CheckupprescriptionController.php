<?php

namespace App\Http\Controllers\Api;

use App\Checkupprescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class CheckupprescriptionController extends Controller
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


    public function storeCheckupPrescriptionPDF(Request $request, Checkupprescription $checkupprescription)
    {
        $this->validate($request, [
           'contents' => 'required'
        ]);

        $patientcheckup = $checkupprescription->patientcheckup;

        $checkupprescription->status = 1;
        $checkupprescription->contents = $request->contents;

        $data = [
            "doctor" => $patientcheckup->doctor,
            "patient" => $patientcheckup->patient,
            "prescription" => json_encode($request->contents)
        ];

        $pdf = PDF::loadView('pdf.prescription.checkupprescription', $data);
        $pdf->save($checkupprescription->code);

        $checkupprescription->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Checkupprescription  $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function show(Checkupprescription $checkupprescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checkupprescription  $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkupprescription $checkupprescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checkupprescription  $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkupprescription $checkupprescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checkupprescription  $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkupprescription $checkupprescription)
    {
        //
    }
}
