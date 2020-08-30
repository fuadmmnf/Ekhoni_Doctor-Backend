<?php


namespace App\Http\Controllers\Handlers\Checkup;

use App\Doctor;
use App\Doctorschedule;
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

    private function sendPushNotification($deviceIds, $title, $message, $data)
    {

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
            'authorization: key=' . config('firebase.gcm_key'),
            'content-type: application/json'
        ];

        $postdata = '{
            "to" : ' . $deviceIds . ',
                "notification" : {
                    "title":"' . $title . '",
                    "text" : "' . $message . '"
                },
            "data" : ' . json_encode($data) . '
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('GCM error: ' . curl_error($ch));
        }


        curl_close($ch);

        return $result;
    }

    public function createCallRequest(Patientcheckup $patientcheckup, $isDoctorCalling)
    {
        $doctor = $patientcheckup->doctor;
        $doctor->status = 1; // in call
        $doctor->save();

        $patient = $patientcheckup->patient;
        $room = $doctor->bmdc_number . '_' . $patient->code;
        $access_token = $this->generate_token($room);

        $data = [
            'access_token' => $access_token,
            'room_name' => $room,
            'caller_name' => ($isDoctorCalling) ? $doctor->name : $patient->name,
            'checkup_code' => $patientcheckup->code,
            'time' => Carbon::now()->toDateTimeString()
        ];

        $addedDocRef = $this->db->collection($isDoctorCalling ? 'doctorcall' : 'patientcall')
            ->document(($isDoctorCalling) ? $patient->user->code : $doctor->user->code)
            ->set($data);


        $this->sendPushNotification(($isDoctorCalling)? $patient->user->device_ids: $doctor->user->device_ids, "Incoming Call", "Call will prevail for 30seconds", $data);

        $callLogs = $patientcheckup->call_log ? json_decode($patientcheckup->call_log, true) : [];
        $callLogs[] = Carbon::now();
        $patientcheckup->call_log = json_encode($callLogs);
        $patientcheckup->save();

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


    public function checkDoctorSchedulesAndSetActiveStatus(Doctor $doctor)
    {
        $currentTime = Carbon::now();
        $doctorSchedule = Doctorschedule::where('doctor_id', $doctor->id)
            ->where('end_time', '>', $currentTime)
            ->where('start_time', '<', $currentTime)
            ->first();
        $doctor->status = $doctorSchedule != null;
        $doctor->save();
    }


}
