<?php

namespace App\Http\Controllers\Api;

use App\Doctor;
use App\Http\Controllers\Handlers\SmsHandler;
use App\Http\Controllers\Handlers\TokenUserHandler;
use App\Http\Controllers\Controller;
use App\Otpcode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function getUserForAdmin(User $user)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user') &&
            !$this->user->hasRole('admin:doctor')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $user = $this->getUserType($user);
        return response()->json($user);
    }

    /**
     * _Fetch User_
     *
     * Get User. !!token_required
     *
     *
     * @urlParam  user required The user id.
     *
     *
     * @response  200 {
     * "id": 4,
     * "mobile": "8801156572072",
     * "code": "WjUGehPy9542R2hx",
     * "status": 0,
     * "is_agent": 0,
     * "agent_percentage": 0,
     * "balance": 0,
     * "device_ids": "[\"fb805ef2-2747-4ea6-bf8c-128a32aa5d40\"]",
     * "created_at": "2020-08-06T11:24:40.000000Z",
     * "updated_at": "2020-08-06T11:49:32.000000Z",
     * "token": "33|xRwEzBwe74QeWiWUoboxgOQtFBsr82qgf6iknRoOxphBX8Pp4PwgAy6nHw1ZGyMcpYPEe62S7VphC3km",
     * "roles": [
     * {
     * "id": 7,
     * "name": "doctor",
     * "guard_name": "web",
     * "created_at": "2020-07-28T19:18:47.000000Z",
     * "updated_at": "2020-07-28T19:18:47.000000Z",
     * "pivot": {
     * "model_id": 4,
     * "role_id": 7,
     * "model_type": "App\\User"
     * }
     * }
     * ],
     * "doctor": {
     * }
     * }
     */
    public function getUser(Request $request, User $user)
    {
        if (!$this->user || $this->user->id != $user->id) {
            return response()->json('Forbidden Access', 403);
        }

        $user = $this->getUserType($this->user);
        $user->token = $request->bearerToken();
        return response()->json($user);
    }


    public function getPatientUsers($is_agent)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:user')
        ) {
            return response()->json('Forbidden Access', 403);
        }

        $patientUsers = User::where('is_agent', $is_agent)->whereHas("roles", function ($q) {
            $q->where("name", "patient");
        })->paginate(20);

        return response()->json($patientUsers);
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


    public function validateAuthenticationToken(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required| min:11| max: 14',
            'otp_code' => 'required'
        ]);

        $otprequest = Otpcode::where('mobile', $request->mobile)->first();
        if ($otprequest->code == $request->otp_code) {
            Otpcode::where('mobile', $request->mobile)->delete();
            return response()->noContent();
        }
        return response()->json('invalid code', 401);

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
     * @bodyParam device_first_login boolean required The boolean representation to indicate if request in from new device, then otp is required.
     * @bodyParam password string The password for doctor auth.
     * @bodyParam device_id string The FCM token of user device.
     *
     *
     * @response  200 {
     * "id": 1,
     * "mobile": "8801156572077",
     * "code": "JLel4822mGDwmHmG",
     * "status": 0,
     * "is_agent": 0,
     * "agent_percentage": 0,
     * "balance": 0,
     * "device_ids": null,
     * "created_at": "2020-09-12T07:27:08.000000Z",
     * "updated_at": "2020-09-12T07:27:08.000000Z",
     * "token": "3|aKHdR7VFP8EPXpDhGfrcBSBWKtahD30LFE4Yp6fj",
     * "admin": {
     * "user_id": 10,
     * "doctortype_id": 2,
     * "name": "doctorname",
     * "bmdc_number": "0000000001",
     * "rate": 100,
     * "offer_rate": 100,
     * "report_followup_rate": 100,
     * "gender": 0,
     * "email": "doctor@google.com",
     * "workplace": "dmc",
     * "designation": "trainee doctor",
     * "medical_college": "dmc",
     * "other_trainings": "sdaosdmoaismdioasmdioas",
     * "postgrad": "dmc",
     * "updated_at": "2020-07-10T14:57:19.000000Z",
     * "created_at": "2020-07-10T14:57:19.000000Z",
     * "id": 4,
     * "activation_status": 1,
     * "payment_style": 1
     * }
     * }
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
            'otp_code' => 'sometimes',
            'password' => 'sometimes| min: 6',
            'is_patient' => 'required| boolean',
            'device_first_login' => 'required| boolean',
            'device_id' => 'sometimes'
        ]);

        if ($request->is_patient || $request->device_first_login) {
            $otprequest = Otpcode::where('mobile', $request->mobile)
                ->where('code', $request->otp_code)
                ->first();

            if (!$otprequest) {
                return response()->json('otp verification code mismatch', 401);
            }
        }


        Otpcode::where('mobile', $request->mobile)->delete();

        $user = User::where('mobile', $request->mobile)->first();
        $tokenUserHandler = new TokenUserHandler();

        //retrive existing user
        if ($user) {
            if (!$request->is_patient && !Hash::check($request->password, $user->password)) {
                return response()->json("No Such User Found", 401);
            }

            $user = $this->getUserType($tokenUserHandler->regenerateUserToken($user, $request->has('device_id') ? $request->device_id : ""));
            return response()->json($user, 200);
        }

        //create general user
        if ($request->is_patient) {
            $newUser = $tokenUserHandler->createUser($request->mobile, $request->has('device_id') ? $request->device_id : "");
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
        $user->agent_percentage = ($request->is_agent) ? $request->agent_percentage / 100 : 0.0;
        $user->save();

        return response()->noContent();
    }

    /**
     * _Change User Password_
     *
     * Change the user password. Deletes all previous tokens. !! token required | super_admin, doctor
     *
     * @urlParam  user required The ID of the user.
     * @bodyParam old_password string required User previous password.
     * @bodyParam password string required User new password.
     * @bodyParam password_confirmation string required User password confirmation.
     *
     *
     * @response  401 {"error" : "Incorrect password"}
     * @response  204 {}
     */
    public function changePassword(Request $request, User $user)
    {
        if (!$this->user ||
            !$this->user->hasRole('super_admin') &&
            !$this->user->hasRole('admin:doctor') &&
            !($this->user->hasRole('doctor') && $this->user->id == $user->id)

        ) {
            return response()->json('Forbidden Access', 403);
        }

        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required| min: 6| confirmed'
        ]);

        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            $user->tokens()->delete();
            $tokenUserHandler = new TokenUserHandler();
            $user = $this->getUserType($tokenUserHandler->regenerateUserToken($user, ""));

            return response()->json($user);
        }
        return response()->json(["error" => "Incorrect password"], 401);
    }

    public function handleForgottenPassword(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required',
            'otp_code' => 'required'
        ]);

        $user = User::where('mobile', $request->mobile)->firstOrFail();


        $otprequest = Otpcode::where('mobile', $request->mobile)
            ->where('code', $request->otp_code)
            ->first();
        if (!$otprequest) {
            return response()->json('otp verification code mismatch', 401);
        }
        Otpcode::where('mobile', $request->mobile)->delete();

        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= mt_rand(0, 9);
        }
        $user->password = Hash::make($code);
        $user->save();


        $smsHandler = new SmsHandler();
        $message = "'Ekhoni Daktar' new access password code: {$code}.";

        $smsHandler->send_sms($user->mobile, $message);
        $user->tokens()->delete();
        return response()->noContent();
    }
}
