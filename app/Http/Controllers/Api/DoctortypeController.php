<?php

namespace App\Http\Controllers\Api;

use App\Doctortype;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DoctortypeController extends Controller
{

    public function __construct(Request $request)
    {
        $publicMethods = ['index'];
        $adminMethods = ['store'];
        $requestMethod = explode('@', Route::currentRouteAction())[1];

        $this->middleware('auth:sanctum')->except($publicMethods);
        if(!in_array($requestMethod, $publicMethods)){
            $this->user = $request->user('sanctum');
            if($this->user->hasRole('super_admin') || $this->user->hasRole('admin:doctor')){
                $this->middleware('role:super_admin|admin:doctor')->only($adminMethods);
            }
        }
    }
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
            'type' => 'required| numeric',
            'specialization' => 'required| min:1',
        ]);
        $newDoctorType = new Doctortype();
        $newDoctorType->type = $request->type;
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
