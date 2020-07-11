<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use App\Patientprescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\File;


class PatientprescriptionController extends Controller
{
    protected $user;

    /**
     * PatientprescriptionController constructor.
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }


    /**
     * _Get Patient Prescriptions_
     *
     * Fetch patient prescriptinos. !! token required | doctor
     *
     *
     * @urlParam patient int required The Id of patient.
     *
     * @response  200 [
     * {
     * "id": 14,
     * "patient_id": 1,
     * "code": "aQDaDugHpPUndNGN",
     * "prescription_path": "assets/images/patients/1/prescriptions/aQDaDugHpPUndNGN1594494657.png",
     * "created_at": "2020-07-11T19:10:57.000000Z",
     * "updated_at": "2020-07-11T19:10:57.000000Z"
     * }
     * ]
     */
    public function getPatientPrescriptionByPatient(Patient $patient)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor')) {
            return response()->json('Forbidden Access', 403);
        }
        $patientPrescriptions = Patientprescription::where('patient_id', $patient->id)->get();
        return response()->json($patientPrescriptions);
    }


    /**
     * _Store Patientprescription_
     *
     * Patientprescription store endpoint [Must be multipart/form-data request with image file], User must provide prescription for registered patients, returns patientprescription instance. !! token required | patient
     *
     *
     * @bodyParam patient_id int required The patient id associated with prescription.
     * @bodyParam  prescription image required The prescription image of patient.
     *
     *
     * @response  201 {
     * "patient_id": 1,
     * "code": "aQDaDugHpPUndNGN",
     * "prescription_path": "assets/images/patients/1/prescriptions/aQDaDugHpPUndNGN1594494657.png",
     * "updated_at": "2020-07-11T19:10:57.000000Z",
     * "created_at": "2020-07-11T19:10:57.000000Z",
     * "id": 14
     * }
     *
     *
     */
    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'patient_id' => 'required| numeric',
            'prescription' => 'required| image| max:4096',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        if ($patient->user->id != $this->user->id) {
            return response()->json('User can only provide prescription for own patient', 403);
        }

        $newPatientPrescription = new Patientprescription();
        $newPatientPrescription->patient_id = $patient->id;
        do {
            $code = Str::random(16);
            $patientprescription = Patientprescription::where('code', $code)->first();
        } while ($patientprescription);
        $newPatientPrescription->code = $code;

        $prescription = $request->file('prescription');
        $location = 'assets/images/patients/' . $patient->id . '/prescriptions/' . $newPatientPrescription->code . time() . '.png';
//        dd($prescription->getExtension());
        Storage::put($location, $prescription->get());
        $newPatientPrescription->prescription_path = $location;

        $newPatientPrescription->save();

        return response()->json($newPatientPrescription, 201);
    }


    public function destroy(Patientprescription $patientprescription)
    {
        //
    }
}
