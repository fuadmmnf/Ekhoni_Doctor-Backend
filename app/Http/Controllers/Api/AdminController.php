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

/**
 * @group  Admin management
 *
 * APIs related to Admin
 */
class AdminController extends Controller
{


    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:super_admin'])->except('authenticateAdmin');
    }


    /**
     * _Fetch admin roles_
     *
     * Fetch admin roles list related to sectors of resource in admin panel. !! token required | super_admin
     *
     * @response  [
     * {
     * "id": 2,
     * "name": "admin:doctor",
     * "guard_name": "web",
     * "created_at": "2020-07-09T18:23:36.000000Z",
     * "updated_at": "2020-07-09T18:23:36.000000Z"
     * },
     * ]
     */
    public function loadAllAdminRoles()
    {
        $adminRoles = Role::whereNotIn('name', ['super_admin', 'doctor', 'patient'])->get();
        return response()->json($adminRoles);
    }


    /**
     * Authenticate Admin
     *
     * Admin login endpoint, returns access_token for admin user
     *
     *
     * @bodyParam mobile string required The mobile of the user. Example: 01955555555
     * @bodyParam  password string required The password. Example: secret123
     *
     *
     * @response  "4|Bgl6fz2j3RW4oMZ2mFvrxzbfbHOiScdCmb3jMwyOnhSemIf8eYVJwHnHbVSJ0l2tfG5ClsFulVBeW76A"
     */
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

    /**
     * _Create Admin_
     *
     * Admin store endpoint, returns admin instance along with access_token. !! token required | super_admin
     *
     *
     * @bodyParam name string required The name of the admin. Example: fuad
     * @bodyParam  mobile string required The mobile required to create user object. Example: 01955555555
     * @bodyParam  email string required The email. Example: fuad@gmail.com
     * @bodyParam  password string required The password. Example: secret123
     * @bodyParam  roles array required The list of strings defining the roles of the admin. Example: ['admin:doctor', 'admin:yser]
     *
     *
     * @response  201{
     * "user_id": 2,
     * "name": "fuad",
     * "email": "fuad@gmail.com",
     * "updated_at": "2020-07-09T20:12:00.000000Z",
     * "created_at": "2020-07-09T20:12:00.000000Z",
     * "id": 2,
     * "token": "5|4k8uIbvxTsdGkL2KF2yA6IA4BL3SkqwBcyWXxYN6C7U9p2sfXzkuDMnmQFwAvh0BpwTHWFpg9I4vI0Hb"
     * }
     */
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
        $newAdmin = new Admin();
        $newAdmin->user_id = $user->id;
        $newAdmin->name = $request->name;
        $newAdmin->email = $request->email;
        $newAdmin->password = Hash::make($request->password);
        $newAdmin->save();
        $user->assignRole($request->roles);
        $newAdmin->token = $user->token;

        return response()->json($newAdmin, 201);
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
