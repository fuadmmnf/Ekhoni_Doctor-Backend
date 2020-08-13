<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Patient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * @group  Patient management
 *
 * APIs related to patients
 */
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

    /**
     *_Fetch User default Patient Profile_
     *
     * Fetch default user patient. !! token required | admin:user, patient
     *
     * @urlParam  user required The User ID of patients.
     *
     * @response  200 {
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
    public function getUserDefaultPatientProfile(User $user)
    {
        if (!$this->user ||
            !$this->user->hasRole('admin:user') &&
            !($this->user->hasRole('patient') && $this->user->id == $user->id)
        ) {
            return response()->json('Forbidden Access', 403);
        }


        $userProfile = Patient::where('user_id', $user->id)->first();
        if (!$userProfile) {
            return response()->json(['status' => false], 400);
        }
        return response()->json($userProfile, 200);
    }

    /**
     *_Fetch Patients By User_
     *
     * Fetch Patients By User. !! token required | admin:user, patient
     *
     * @urlParam  user required The User ID of patients.
     *
     * @response  200 [
     * {
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
     * ]
     */
    public function getPatientsByUser(User $user)
    {
        if (!$this->user ||
            !$this->user->hasRole('admin:user') &&
            !($this->user->hasRole('patient') && $this->user->id == $user->id)
        ) {
            return response()->json('Forbidden Access', 403);
        }


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
     * @bodyParam  address string The patient address.
     * @bodyParam  blood_group string required The patient blood group. Example: "B+ve"
     * @bodyParam  blood_pressure string required The patient blood pressure. Example: "90-150"
     * @bodyParam  cholesterol_level string required The patient cholesterol level. Example: "dont know the readings :p"
     * @bodyParam  height string The patient height.
     * @bodyParam  weight string The patient weight.
     *
     *
     * @response  201 {
     * "user_id": 3,
     * "name": "required",
     * "age": 23,
     * "gender": 1,
     * "address": "address"
     * "code": "RMshPimgOz6yKecP",
     * "blood_group": "B+ve",
     * "blood_pressure": "90-150",
     * "cholesterol_level": "60",
     * "height": "5'10''",
     * "weight": "80",
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
            'address' => 'sometimes| nullable',
            'blood_group' => 'sometimes| nullable',
            'blood_pressure' => 'sometimes| nullable',
            'cholesterol_level' => 'sometimes| nullable',
            'height' => 'sometimes| nullable',
            'weight' => 'sometimes| nullable',
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

        if ($request->has('address')) {
            $newPatient->address = $request->address;
        }

        if ($request->has('blood_group')) {
            $newPatient->blood_group = $request->blood_group;
        }
        if ($request->has('blood_pressure')) {
            $newPatient->blood_pressure = $request->blood_pressure;
        }
        if ($request->has('cholesterol_level')) {
            $newPatient->cholesterol_level = $request->cholesterol_level;
        }
        if ($request->has('height')) {
            $newPatient->height = $request->height;
        }
        if ($request->has('weight')) {
            $newPatient->weight = $request->weight;
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
     * @urlParam   patient required The patient id.
     * @bodyParam  name string The patient name.
     * @bodyParam  address string The patient address.
     * @bodyParam  age int The patient age.
     * @bodyParam  blood_group string The patient blood group. Example: "B+ve"
     * @bodyParam  blood_pressure string The patient blood pressure. Example: "90-150"
     * @bodyParam  cholesterol_level string The patient cholesterol level. Example: "dont know the readings :p"
     * @bodyParam  height string The patient height.
     * @bodyParam  weight string The patient weight.
     *
     *
     * @response  204 ""
     *
     */
    public function update(Request $request, Patient $patient)
    {
        if (!$this->user ||
            !$this->user->hasRole('patient')) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'name' => 'sometimes| numeric',
            'age' => 'sometimes| numeric',
            'address' => 'sometimes| numeric',
            'blood_group' => 'sometimes',
            'blood_pressure' => 'sometimes',
            'cholesterol_level' => 'sometimes',
            'height' => 'sometimes',
            'weight' => 'sometimes',
        ]);

        if ($request->has('name')) {
            $patient->name = $request->name;
        }
        if ($request->has('age')) {
            $patient->age = $request->age;
        }
        if ($request->has('address')) {
            $patient->address = $request->address;
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

        if ($request->has('height')) {
            $patient->height = $request->height;
        }
        if ($request->has('weight')) {
            $patient->weight = $request->weight;
        }
        $patient->save();

        return response()->noContent();
    }

    /**
     * _Change Patient Image_
     *
     * Update patient image (Multipart Request)!! token required | super_admin, admin:user, patient
     *
     *
     * @urlParam  patient required The ID of the patient.
     * @bodyParam  image file required The patient image file.
     *
     *
     * @response  204 ""
     */
    public function changePatientImage(Request $request, Patient $patient)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user') &&
            !$this->user->hasRole('patient')
        ) {
            return response()->json('Forbidden Access', 403);
        }

//        dd('asd');
        $image_path = public_path('/images/users/patients/' . $patient->image);
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        $image = $request->file('image');
        $filename = $patient->code . '_' . time() . '.' . $image->getClientOriginalExtension();
        $location = public_path('/images/users/patients/' ) . $filename;
        Image::make($image)->resize(250, 250)->save($location);
        $patient->image = $filename;

        $patient->save();
        return response()->noContent();
    }

}
