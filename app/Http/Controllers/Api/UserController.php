<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Http\Controllers\Handlers\SmsHandler;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\Otpcode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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


    private function getUserType(User $user)
    {
        if ($user->hasRole('doctor')) {
            $user->doctor;
        } elseif (!$user->hasRole('patient')) {
            $user->admin;
        }

        return $user;
    }

    public function fetchUserTypeFromToken()
    {
        if (!$this->user) {
            return response()->json('Unauthorized', 401);
        }

        $user = $this->getUserType($this->user);
        return response()->json($user);
    }


    /**
     * Send OTP to user mobile
     *
     * Get User mobile and send otp.
     *
     *
     * @bodyParam mobile string required The mobile of the user. Example: 8801955555555
     *
     *
     * @response  201 {
     * "respose": "otp token created"
     * }
     */
    public function sendAuthenticationToken(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required| min:11| max: 14',
        ]);

        Otpcode::where('mobile', $request->mobile)->delete();

        $newOtpcode = new Otpcode();
        $newOtpcode->mobile = $request->mobile;

        $code = '';
        for ($i = 0; $i < 4; $i++) {
            $code .= mt_rand(0, 9);
        }
        $newOtpcode->code = $code;
        $newOtpcode->save();


        $smsHandler = new SmsHandler();
//        $message = "<#> 'Ekhoni Dakar' OTP code is : {$newOtpcode->code}. App Id: [EZtoGwmcCrh]";
        $message = "'Ekhoni Daktar' OTP code is: {$newOtpcode->code}.";

        $smsHandler->send_sms($newOtpcode->mobile, $message);

        return response()->json(["response" => "otp token created"], 201);
    }


    /**
     * Create/Retrieve User
     *
     * Get User object using mobile| create new if not present
     *
     *
     * @bodyParam mobile string required The mobile of the user. Example: 8801955555555
     * @bodyParam otp_code string required The 4 digit access otp token sent via sms. Example: 1234
     * @bodyParam is_patient boolean required The boolean representation to indicate if request in from general user. So that if user not found new user will be created. Example: true
     *
     *
     * @response  "4|Bgl6fz2j3RW4oMZ2mFvrxzbfbHOiScdCmb3jMwyOnhSemIf8eYVJwHnHbVSJ0l2tfG5ClsFulVBeW76A"
     * @response  201 {
     * "mobile": "8801955555555",
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
            'otp_code' => 'required',
            'is_patient' => 'required| boolean',
        ]);

        $otprequest = Otpcode::where('mobile', $request->mobile)
            ->where('code', $request->otp_code)
            ->first();

        if (!$otprequest) {
            return response()->json('otp verification code mismatch', 401);
        }

        Otpcode::where('mobile', $request->mobile)->delete();

        $user = User::where('mobile', $request->mobile)->first();
        $tokenUserHandler = new TokenUserHandler();

        //retrive existing user
        if ($user) {
            $user = $this->getUserType($tokenUserHandler->regenerateUserToken($user));
            return response()->json($user, 200);
        }

        //create general user
        if ($request->is_patient) {
            $newUser = $tokenUserHandler->createUser($request->mobile);
            $newUser->assignRole('patient');

//        unset($newUser->roles);
            return response()->json($newUser, 201);
        }

        return response()->json("No Such User Found", 401);
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
        $user->agent_percentage = ($request->is_agent) ? $request->agent_percentage : 0.0;
        $user->save();

        return response()->noContent();
    }


}
