<?php

namespace App\Http\Controllers\Api;

use App\Checkupprescription;
use App\Doctor;
use App\Http\Controllers\Controller;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class CheckupprescriptionController extends Controller
{
    protected $user;

    /**
     * PatientcheckupController constructor.
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }


    /**
     * _Fetch Pending Checkupprescription By Doctor_
     *
     * Fetch pending doctor checkupprescriptions. !! token required| super_admin, admin:doctor, doctor
     *
     * @urlParam doctor required The doctor id associated with the prescriptions.
     *
     *
     * @response  200 [
     * {
     * "id": 1,
     * "patientcheckup_id": 1,
     * "status": 0,
     * "code": "ae12e12f12f",
     * "contents": null,
     * "prescription_path": "",
     * "created_at": null,
     * "updated_at": null,
     * "patientcheckup": {
     * "id": 1,
     * "patient_id": 1,
     * "doctor_id": 2,
     * "code": "aabbaaaabb",
     * "start_time": "2020-08-16 20:42:05",
     * "end_time": null,
     * "doctor_rating": 5,
     * "patient_rating": 5,
     * "created_at": null,
     * "updated_at": null,
     * "patient": {
     * "id": 1,
     * "user_id": 5,
     * "name": "patient name",
     * "code": "aaaaaaaaaa",
     * "status": 0,
     * "age": "21",
     * "gender": 0,
     * "address": "asdasdasdasdasdasdasdasd",
     * "blood_group": "B -ve",
     * "blood_pressure": "100-150",
     * "cholesterol_level": "120",
     * "height": "5'11''",
     * "weight": "90",
     * "image": "aaaaaaaaaa_1597428565.png",
     * "created_at": "2020-08-09T06:25:34.000000Z",
     * "updated_at": "2020-08-14T18:09:26.000000Z"
     * }
     * }
     * }
     * ]
     */
    public function getPendingPrescriptionByDoctor(Doctor $doctor)
    {
        if (!$this->user ||
            !($this->user->hasRole('doctor') && $doctor->user->id == $this->user->id) &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }
        $pendingCheckupPrescriptions = Checkupprescription::where('status', 0)->get();
        $pendingCheckupPrescriptions = $pendingCheckupPrescriptions->filter(function ($checkupPrescription) use ($doctor) {
            $isDoctorMatching = $checkupPrescription->patientcheckup->doctor->id == $doctor->id;
            if ($isDoctorMatching) {
                $checkupPrescription->patientcheckup->patient;
            }
            unset($checkupPrescription->patientcheckup->doctor);
            return $isDoctorMatching;
        });
        return response()->json($pendingCheckupPrescriptions);
    }


    /**
     * _Fetch Pending Checkupprescription By Patient_
     *
     * Fetch pending patient checkupprescriptions. !! token required| super_admin, admin:patient, patient
     *
     * @urlParam patient required The patient id associated with the prescriptions.
     *
     *
     * @response  200 [
     * {
     * "id": 1,
     * "patientcheckup_id": 1,
     * "status": 0,
     * "code": "ae12e12f12f",
     * "contents": null,
     * "prescription_path": "",
     * "created_at": null,
     * "updated_at": null,
     * "patientcheckup": {
     * "id": 1,
     * "patient_id": 1,
     * "doctor_id": 2,
     * "code": "aabbaaaabb",
     * "start_time": "2020-08-16 20:42:05",
     * "end_time": null,
     * "doctor_rating": 5,
     * "patient_rating": 5,
     * "created_at": null,
     * "updated_at": null,
     * "patient": {
     * "id": 1,
     * "user_id": 5,
     * "name": "patient name",
     * "code": "aaaaaaaaaa",
     * "status": 0,
     * "age": "21",
     * "gender": 0,
     * "address": "asdasdasdasdasdasdasdasd",
     * "blood_group": "B -ve",
     * "blood_pressure": "100-150",
     * "cholesterol_level": "120",
     * "height": "5'11''",
     * "weight": "90",
     * "image": "aaaaaaaaaa_1597428565.png",
     * "created_at": "2020-08-09T06:25:34.000000Z",
     * "updated_at": "2020-08-14T18:09:26.000000Z"
     * }
     * }
     * }
     * ]
     */
    public function getPendingPrescriptionByPatient(Patient $patient)
    {
        if (!$this->user ||
            !($this->user->hasRole('patient') && $patient->user->id == $this->user->id) &&
            !$this->user->hasRole('admin:patient') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }

        $pendingCheckupPrescriptions = Checkupprescription::where('status', 0)->get();
        $pendingCheckupPrescriptions = $pendingCheckupPrescriptions->filter(function ($checkupPrescription) use ($patient) {
            $isPatientMatching = $checkupPrescription->patientcheckup->patient->id == $patient->id;
            if ($isPatientMatching) {
                $checkupPrescription->patientcheckup->doctor;
            }
            unset($checkupPrescription->patientcheckup->patient);
            return $isPatientMatching;
        });
        return response()->json($pendingCheckupPrescriptions);
    }



    public function servePrescriptionPDF(Checkupprescription $checkupprescription){
        $patientcheckup = $checkupprescription->patientcheckup;

        if (!$this->user ||
            !($this->user->hasRole('doctor') && $this->user->id == $patientcheckup->doctor->id) &&
            !($this->user->hasRole('patient') && $this->user->id == $patientcheckup->patient->id) &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('admin:patient') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }

        return response(Storage::get($checkupprescription->prescription_path))->header('Content-type','application/pdf');
    }


    public function storeCheckupPrescriptionPDF(Request $request, Checkupprescription $checkupprescription)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor') &&
            !$this->user->hasRole('admin:doctor') &&
            !$this->user->hasRole('super_admin')) {

            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'prescription' => 'required'
        ]);

        $patientcheckup = $checkupprescription->patientcheckup;

        $checkupprescription->status = 1;
        $checkupprescription->contents = $request->contents;
        $checkupprescription->prescription_path = 'app/assets/reports/prescriptions/' . $checkupprescription->code . time() . '.pdf';
        $data = [
            "doctor" => $patientcheckup->doctor,
            "patient" => $patientcheckup->patient,
            "checkup" => $patientcheckup,
            "prescription" => $request->prescription
        ];
        $pdf = PDF::loadView("pdf.prescriptions.checkupprescription", $data);
        $pdf->save(storage_path($checkupprescription->prescription_path));

        $checkupprescription->save();

        unset($checkupprescription->patientcheckup);
        return response()->json($checkupprescription);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Checkupprescription $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function show(Checkupprescription $checkupprescription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Checkupprescription $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkupprescription $checkupprescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Checkupprescription $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkupprescription $checkupprescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Checkupprescription $checkupprescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkupprescription $checkupprescription)
    {
        //
    }
}
