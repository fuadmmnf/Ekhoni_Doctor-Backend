<?php


namespace App\Http\Controllers\Handlers\Checkup;

use App\Patientcheckup;
use Carbon\Carbon;
use Kreait\Firebase\Factory;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class CheckupCallHandler
{

    private $db;
    /**
     * CheckupCallHandler constructor.
     */
    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path() . '/' . env('FIREBASE_CREDENTIALS'));
        $this->db = $factory->createFirestore()->database();
    }

    private function generate_token($room)
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
        $grant->setRoom($room);
        $token->addGrant($grant);

        // Serialize the token as a JWT
        return $token->toJWT();
    }


    public function createCallRequest(Patientcheckup $patientcheckup, $isDoctorCalling){
        $doctor = $patientcheckup->doctor;
        $patient = $patientcheckup->patient;
        $room = $doctor->bmdc_number . '_' . $patient->code;
        $access_token = $this->generate_token($room);

        $data = [
            'access_token' => $access_token,
            'room_name' => $room,
            'caller_name' => ($isDoctorCalling)? $doctor->name: $patient->name,
            'checkup_code' => $patientcheckup->code,
            'time' => Carbon::now()
        ];

        $addedDocRef = $this->db->collection($isDoctorCalling? 'doctorcall': 'patientcall')
            ->document(($isDoctorCalling)? $patient->user->code: $doctor->user->code)
            ->set($data);

        error_log(json_encode($addedDocRef));
        return $data;
    }

    public function terminateCallSession(Patientcheckup $patientcheckup)
    {
        $this->db->collection("doctorcall")
        ->document($patientcheckup->patient->user->code)
        ->delete();

        $this->db->collection("patientcall")
        ->document($patientcheckup->doctor->user->code)
        ->delete();
    }

}
