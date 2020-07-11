<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @group  User management
 *
 * APIs related to User
 */
class UserController extends Controller
{

    protected $user;
    public function __construct(Request $request)
    {
        $this->user = $request->user('sanctum');
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


    /**
     * Create/Retrieve User
     *
     * Get User object using mobile| create new if not present
     *
     *
     * @bodyParam mobile string required The mobile of the user. Example: 01955555555
     *
     *
     * @response  "4|Bgl6fz2j3RW4oMZ2mFvrxzbfbHOiScdCmb3jMwyOnhSemIf8eYVJwHnHbVSJ0l2tfG5ClsFulVBeW76A"
     * @response  201 {
     * "mobile": "01955555555",
     * "code": "mxH8SeGHt4cjWr8R",
     * "updated_at": "2020-07-09T20:44:33.000000Z",
     * "created_at": "2020-07-09T20:44:33.000000Z",
     * "id": 6,
     * "token": "10|gTlkf0Qy4vXkwT51g0BEUqehYEadWonfsKsKPrSnYh7YISkZPFW9DRNZUH0tljrvKAozJTCPgrdtVBnB"
     * }
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required| min:11| max: 14',
        ]);

        $user = User::where('mobile', $request->mobile)->first();
        $tokenUserHandler = new TokenUserHandler();

        if ($user) {
            $token = $tokenUserHandler->regenerateUserToken($user);
            return response()->json($token, 200);
        }

        $newUser = $tokenUserHandler->createUser($request->mobile);
        $newUser->assignRole('patient');
        unset($newUser->roles);
        return response()->json($newUser, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }


    /**
     * _Alter User Agent permission_
     *
     * Change the user object to modify the is_agent & agent_percentage field. !! token required | super_admin, admin:user
     *
     * @urlParam  user required The ID of the user.
     * @bodyParam is_agent boolean required Whether user is agent. Example: true
     * @bodyParam agent_percentage double required Agent commission percentage for each call. Example: 2.5
     *
     *
     * @response  400 "validation error"
     * @response  204 {}
     */
    public function changeUserAgentPermission(Request $request, User $user)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user')
        ) {
            return response()->json('Forbidden Access', 403);
        }
        $validator = Validator::make($request->all(), [
            'is_agent' => 'required',
            'agent_percentage' => 'required| numeric| between: 0,100',
        ]);
        if ($validator->fails()) {
            return response()->json('validation error', 400);
        }

        $user->is_agent = $request->is_agent;
        $user->agent_percentage = ($request->is_agent)? $request->agent_percentage: 0.0;
        $user->save();

        return response()->noContent();
    }

    public function update(Request $request, User $user)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

    }
}
