<?php


namespace App\Http\Controllers\Handlers\Firebase;

use Kreait\Firebase\Firestore;

class FirestoreHandler
{
    private $db;

    public function __construct(Firestore $firestore)
    {
        $this->db = $firestore->database();
    }


    public function addCheckupDocument($callee, $data, $isAppointment)
    {
        $addedDocRef = $this->db->collection($isAppointment? 'doctorcall': 'patientcall')
            ->document('' . $callee->user->code)
            ->set($data);
    }
}
