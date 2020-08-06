<?php


namespace App\Http\Controllers\Handlers;


use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenUserHandler
{

    public function createUser($mobile, $deviceId): User {
        $newUser = new User();


        $newUser->mobile = $mobile;
        do
        {
            $code = Str::random(16);

            $user_code = User::where('code', $code)->first();
        }
        while($user_code);
        $newUser->code = $code;
        $newUser->password = Hash::make($newUser->mobile. $newUser->code);
        if (strlen($deviceId)) {
            $newUser->device_ids = json_encode(array($deviceId));
        }
        $newUser->save();
        $newUser->token = $newUser->createToken($newUser->mobile . $newUser->code)->plainTextToken;

        return $newUser;
    }


    public function regenerateUserToken($user, $deviceId){
        $user->tokens()->delete();
        if (strlen($deviceId)) {
            $deviceIds = json_decode($user->device_ids)?? array();
            if (!in_array($deviceId, $deviceIds)) {
                $deviceIds[] = $deviceId;
                $user->device_ids = json_encode($deviceIds);
                $user->save();
            }

        }

        $user->token = $user->createToken($user->mobile . $user->code)->plainTextToken;
        return $user;
    }



}
