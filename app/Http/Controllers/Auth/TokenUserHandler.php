<?php


namespace App\Http\Controllers\Auth;


use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TokenUserHandler
{

    public function createUser($mobile, $scope): User {
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
        $newUser->save();
        $newUser->token = $newUser->createToken($newUser->mobile . $newUser->code, $scope)->plainTextToken;

        return $newUser;
    }
}
