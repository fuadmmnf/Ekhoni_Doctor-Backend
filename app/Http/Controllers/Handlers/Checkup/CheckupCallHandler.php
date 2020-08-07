<?php


namespace App\Http\Controllers\Handlers\Checkup;

use App\Http\Controllers\Handlers\Firebase\FirestoreHandler;
use Kreait\Firebase\Factory;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class CheckupCallHandler
{
    private function generate_token()
    {
        // Substitute your Twilio Account SID and API Key details
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $apiKeySid = env('TWILIO_API_KEY_SID');
        $apiKeySecret = env('TWILIO_API_KEY_SECRET');

        $identity = uniqid();

        // Create an Access Token
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity
        );

        // Grant access to Video
        $grant = new VideoGrant();
        $grant->setRoom('demo');
        $token->addGrant($grant);

        // Serialize the token as a JWT
        return $token->toJWT();
    }


    public function createCallRequest($caller, $callee, $isAppointment){
        $data = [
            'access_token' => $this->generate_token(),
            'caller_name' => $caller->name
        ];

        $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $firestoreHandler = new FirestoreHandler($factory->createFirestore());
        $firestoreHandler->addCheckupDocument($callee, $data, $isAppointment);
    }
}
