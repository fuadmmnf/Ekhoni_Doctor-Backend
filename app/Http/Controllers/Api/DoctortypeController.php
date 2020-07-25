<?php

namespace App\Http\Controllers\Api;

use App\Doctortype;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * @group  DoctorType management
 *
 * APIs related to DoctorTypes
 */
class DoctortypeController extends Controller
{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
    }

    /**
     * Fetch doctor types
     *
     * Fetch doctor types list.
     *
     * @response  [
     * {
     * "id": 1,
     * "type": 0,
     * "specialization": "cardiology",
     * "created_at": "2020-07-10T10:09:17.000000Z",
     * "updated_at": "2020-07-10T10:09:17.000000Z"
     * }
     * ]
     */
    public function index()
    {
        $doctorTypes = Doctortype::all();
        return response()->json($doctorTypes);
    }

    /**
     * _Create Doctortype_
     *
     * Doctortype store endpoint, returns doctortype instance. !! token required | super_admin, admin:doctor
     *
     *
     * @bodyParam type int required The type indication of doctor. Example: 0 => emergency, 1 => specialist
     * @bodyParam  specialization string required The main field of expertise. Example: "cardiology"
     *
     *
     * @response  201 {
     * "type": "1",
     * "specialization": "cardiology",
     * "updated_at": "2020-07-10T12:16:17.000000Z",
     * "created_at": "2020-07-10T12:16:17.000000Z",
     * "id": 2
     * }
     */
    public function store(Request $request)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }
        $this->validate($request, [
            'name' => 'required| min:1',
        ]);
        $newDoctorType = new Doctortype();
        $newDoctorType->name = $request->name;
        $newDoctorType->save();

        return response()->json($newDoctorType, 201);
    }

    public function show(Doctortype $doctortype)
    {
        //
    }


    public function update(Request $request, Doctortype $doctortype)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Doctortype $doctortype
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctortype $doctortype)
    {
        //
    }
}
