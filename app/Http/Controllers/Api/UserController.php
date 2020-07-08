<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

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
            'mobile' => 'required| unique:users| min:11| max: 14',
        ]);

        $tokenUserHandler = new TokenUserHandler();
        $newUser = $tokenUserHandler->createUser($request->mobile, []);
        if(!$newUser){
            return response()->json("bad request", 400);
        }

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'is_agent' => 'sometimes',
        ]);

        if($request->has('is-agent')){
            $user->is_agent = $request->is_agent;
        }

        $user->save();
        return response()->noContent();
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
