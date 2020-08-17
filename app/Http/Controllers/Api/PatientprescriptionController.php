<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use App\Patientprescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


/**
 * @group  Patientprescription management
 *
 * APIs related to patient prescriptions
 */
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
     * Fetch patient prescriptinos. !! token required | doctor, patient
     *
     *
     * @urlParam patient required The Id of patient.
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
            !$this->user->hasRole('doctor') &&
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }

        if($this->user->patient && $this->user->id != $patient->user->id){
            return response()->json('Forbidden Access', 403);
        }

        $patientPrescriptions = Patientprescription::where('patient_id', $patient->id)->get();
        return response()->json($patientPrescriptions);
    }




   /**
     * _Serve Patient Prescription Image_
     *
     * Fetch patient prescriptino image. !! token required | doctor, patient
     *
     *
     * @urlParam patientprescription required The Id of patientprescription. Example: 14
     *
     *
     */
    public function servePrescriptionImage(Patientprescription $patientprescription)
    {
        if (!$this->user ||
            !$this->user->hasRole('doctor') &&
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }
        $prescriptionPathArr = explode('.', $patientprescription->prescription_path);;
        return response(Storage::get($patientprescription->prescription_path))->header('Content-type','image/'. end($prescriptionPathArr));
    }


    /**
     * _Store Patientprescription_
     *
     * Patientprescription store endpoint [Must be multipart/form-data request with image file], User must provide prescriptions for registered patients, returns patientprescription instance. !! token required | patient
     *
     *
     * @bodyParam patient_id int required The patient id associated with prescriptions.
     * @bodyParam  prescriptions image required The prescriptions image of patient.
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
            'prescriptions' => 'required| image| max:4096',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        if ($patient->user->id != $this->user->id) {
            return response()->json('User can only provide prescriptions for own patient', 403);
        }

        $newPatientPrescription = new Patientprescription();
        $newPatientPrescription->patient_id = $patient->id;
        do {
            $code = Str::random(16);
            $patientprescription = Patientprescription::where('code', $code)->first();
        } while ($patientprescription);
        $newPatientPrescription->code = $code;

        $prescription = $request->file('prescriptions');
        $location = 'assets/images/patients/' . $patient->code . '/prescriptions/' . time() . '.png';
//        dd($prescriptions->getExtension());
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
