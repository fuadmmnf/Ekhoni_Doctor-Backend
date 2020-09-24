<?php


namespace App\Http\Controllers\Handlers\Checkup;

use App\Checkupprescription;
use App\Doctor;
use App\Doctorschedule;
use App\Patientcheckup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class CheckupCallHandler
{

    private $db;
    private $fcm;

    /**
     * CheckupCallHandler constructor.
     */
    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(base_path() . '/' . config('firebase.credentials.file'));
        $this->db = $factory->createFirestore()->database();
        $this->fcm = $factory->createMessaging();
    }

    private function generate_token($room)
    {
        // Substitute your Twilio Account SID and API Key details
        $accountSid = config('twilio.account_sid');
        $apiKeySid = config('twilio.api_sid');
        $apiKeySecret = config('twilio.api_secret');

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


    public function createCallRequest(Patientcheckup $patientcheckup, $isDoctorCalling)
    {
        $doctor = $patientcheckup->doctor;
        $doctor->status = 2; // in call
        $doctor->save();

        $patient = $patientcheckup->patient;
        $room = $doctor->bmdc_number . '_' . $patient->code;

        $data = [
            'access_token' => $this->generate_token($room),
            'room_name' => $room,
            'caller_name' => ($isDoctorCalling) ? $doctor->name : $patient->name,
            'checkup_code' => $patientcheckup->code,
            'time' => Carbon::now()->toDateTimeString()
        ];


        $addedDocRef = $this->db->collection($isDoctorCalling ? 'doctorcall' : 'patientcall')
            ->document(($isDoctorCalling) ? $patient->user->code : $doctor->user->code)
            ->set($data);

        $receivingUser = ($isDoctorCalling) ? $patient->user : $doctor->user;
        $data['type'] = '1'; //1=> call, 2=>others

        $deviceIds = json_decode($receivingUser->device_ids, true);

        if ($deviceIds && count($deviceIds) > 0) {
            $message = CloudMessage::new()->withData($data);
            $sendReport = $this->fcm->sendMulticast($message, json_decode($receivingUser->device_ids));
//        error_log($sendReport->successes()->count());
//        error_log($sendReport->failures()->count());
            if ($sendReport->hasFailures()) {
                foreach ($sendReport->failures()->getItems() as $failure) {
                    Log::error($failure->error()->getMessage());
                }
            }
        }


        $now = Carbon::now();
        $callLogs = $patientcheckup->call_log ? json_decode($patientcheckup->call_log, true) : [];
        $callLogs[] = $now;
        $patientcheckup->call_log = json_encode($callLogs);
        $patientcheckup->start_time = $now;
        $patientcheckup->save();

        $this->createCheckupPrescription($patientcheckup);

        $data['access_token'] = $this->generate_token($room);
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
            ->where('type', 0)
            ->where('end_time', '>', $currentTime)
            ->where('start_time', '<', $currentTime)
            ->first();
        $doctor->status = $doctorSchedule != null;
        $doctor->save();
    }

    private function createCheckupPrescription(Patientcheckup $patientcheckup)
    {
        //create checkupprescription as patientcheckup endtime submitted(indicates end of checkup)
        $prescription = Checkupprescription::where('patientcheckup_id', $patientcheckup->id)->first();
        if (!$prescription) {
            $newCheckupPrescription = new Checkupprescription();
            $newCheckupPrescription->patientcheckup_id = $patientcheckup->id;
            $newCheckupPrescription->status = 0; //initialized(pending content)
            do {
                $code = Str::random(16);
                $checkupPrescription = Checkupprescription::where('code', $code)->first();
            } while ($checkupPrescription);
            $newCheckupPrescription->code = $code;
            $newCheckupPrescription->save();
        }
    }
}
