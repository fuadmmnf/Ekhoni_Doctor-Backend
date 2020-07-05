<?php

namespace App\Http\Controllers\Api;

use App\Doctortype;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctortypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $doctorTypes = Doctortype::all();
        return response()->json($doctorTypes);
    }



    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required| min:1',
            'specialization' => 'required| min:1',
        ]);
        $newDoctorType = new Doctortype();
        $newDoctorType->name = $request->name;
        $newDoctorType->specialization = $request->specialization;
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
     * @param  \App\Doctortype  $doctortype
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctortype $doctortype)
    {
        //
    }
}
