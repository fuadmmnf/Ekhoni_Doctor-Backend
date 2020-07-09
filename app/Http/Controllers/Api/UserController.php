<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:super_admin|admin:user'])->only('changeUserAgentPermission');
        $this->middleware(['auth:sanctum'])->except('store');

    }

    public function index()
    {

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
            'mobile' => 'required| min:11| max: 14',
        ]);

        $user = User::where('mobile', $request->mobile)->first();
        $tokenUserHandler = new TokenUserHandler();

        if($user){
            $token = $tokenUserHandler->regenerateUserToken($user);
            return response()->json($token, 200);
        }

        $newUser = $tokenUserHandler->createUser($request->mobile);
        $newUser->assignRole('patient');

        return response()->json($newUser, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }


    public function changeUserAgentPermission(Request $request, User $user){
        $validator = Validator::make($request->all(), [
            'is_agent' => 'required',
            'agent_percentage' => 'required| numeric| between: 0,100',
        ]);
        if($validator->fails()){
            return response()->json('validation error', 400);
        }

        $user->is_agent = $request->is_agent;
        $user->agent_percentage = $request->agent_percentage;
        $user->save();

        return response()->noContent();
    }

    public function update(Request $request, User $user)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

    }
}
