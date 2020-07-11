<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientController extends Controller
{

    protected $user;

    /**
     * PatientController constructor.
     */
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
        //
    }

    public function getPatientsByUser(User $user)
    {
        $userPatients = Patient::where('user_id', $user->id)->get();
        return response()->json($userPatients);
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

    /**
     * _Create Patient_
     *
     * Patient store endpoint, returns patient instance with user_id set for User instance associated with token.  !! token required | patient
     *
     *
     * @bodyParam name string required The patient name.
     * @bodyParam  age int required The patient age.
     * @bodyParam  gender int required The patient gender. 0 => male, 1 => female
     * @bodyParam  blood_group string required The patient blood group. Example: "B+ve"
     * @bodyParam  blood_pressure string required The patient blood pressure. Example: "90-150"
     * @bodyParam  cholesterol_level string required The patient cholesterol level. Example: "dont know the readings :p"
     *
     *
     * @response  201 {
     * "user_id": 3,
     * "name": "required",
     * "age": 23,
     * "gender": 1,
     * "code": "RMshPimgOz6yKecP",
     * "blood_group": "B+ve",
     * "blood_pressure": "90-150",
     * "cholesterol_level": "60",
     * "updated_at": "2020-07-10T21:30:47.000000Z",
     * "created_at": "2020-07-10T21:30:47.000000Z",
     * "id": 1
     * }
     */
    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'name' => 'required',
            'age' => 'required| numeric',
            'gender' => 'required| numeric',
            'blood_group' => 'sometimes| nullable',
            'blood_pressure' => 'sometimes| nullable',
            'cholesterol_level' => 'sometimes| nullable',
        ]);

        $newPatient = new Patient();
        $newPatient->user_id = $this->user->id;
        $newPatient->name = $request->name;
        $newPatient->age = $request->age;
        $newPatient->gender = $request->gender;

        do {
            $code = Str::random(16);
            $patient = Patient::where('code', $code)->first();
        } while ($patient);
        $newPatient->code = $code;

        if ($request->has('blood_group')) {
            $newPatient->blood_group = $request->blood_group;
        }
        if ($request->has('blood_pressure')) {
            $newPatient->blood_pressure = $request->blood_pressure;
        }
        if ($request->has('cholesterol_level')) {
            $newPatient->cholesterol_level = $request->cholesterol_level;
        }
        $newPatient->save();

        return response()->json($newPatient, 201);
    }



    /**
     * _Update Patient_
     *
     * Patient update endpoint. User associated with token must match with patient user. !! token required | patient
     *
     *
     * @urlParam   patient string required The patient id.
     * @bodyParam  age int required The patient age.
     * @bodyParam  blood_group string required The patient blood group. Example: "B+ve"
     * @bodyParam  blood_pressure string required The patient blood pressure. Example: "90-150"
     * @bodyParam  cholesterol_level string required The patient cholesterol level. Example: "dont know the readings :p"
     *
     *
     * @response  204
     *
     */
    public function update(Request $request, Patient $patient)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'age' => 'sometimes| numeric',
            'blood_group' => 'sometimes',
            'blood_pressure' => 'sometimes',
            'cholesterol_level' => 'sometimes',
        ]);

        if ($request->has('age')) {
            $patient->age = $request->age;
        }
        if ($request->has('blood_group')) {
            $patient->blood_group = $request->blood_group;
        }
        if ($request->has('blood_pressure')) {
            $patient->blood_pressure = $request->blood_pressure;
        }
        if ($request->has('cholesterol_level')) {
            $patient->cholesterol_level = $request->cholesterol_level;
        }
        $patient->save();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Patient $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
