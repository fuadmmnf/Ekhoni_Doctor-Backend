<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{


    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:super_admin']);
    }

    public function index()
    {
        //
    }

    public function loadAllAdminRoles()
    {
        $adminRoles = Role::whereNotIn('name', ['super_admin', 'doctor', 'patient'])->get();
        return response()->json($adminRoles);;
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

    public function authenticateAdmin(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required| min: 11| max: 14',
            'password' => 'required| min: 6',
        ]);

        $user = User::where('mobile', $request->mobile)->first();
        $admin = $user->admin;
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json('invalid credentials', 401);
        }
        $tokenUserHandler = new TokenUserHandler();
        return response()->json($tokenUserHandler->regenerateUserToken($user), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required| unique:users| min: 11| max: 14',
            'email' => 'required',
            'password' => 'required| min: 6',
            'roles' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json('validation error', 400);
        }

        $tokenUserHandler = new TokenUserHandler();
        $user = $tokenUserHandler->createUser($request->mobile);
        $user->assignRole($request->roles);
        $newAdmin = new Admin();
        $newAdmin->user_id = $user->id;
        $newAdmin->name = $request->name;
        $newAdmin->email = $request->email;
        $newAdmin->password = Hash::make($request->password);
        $newAdmin->save();
        $newAdmin->token = $user->token;

        return response()->json($newAdmin, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
