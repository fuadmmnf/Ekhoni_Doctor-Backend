<?php


namespace App\Http\Controllers\Handlers\Firebase;


use App\Doctor;
use App\Patient;
use App\User;
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


        $addedDocRef = $this->db->collection($isAppointment? 'doctor': 'patient')
            ->document('' . $callee->user->code)
            ->collection('checkups')
            ->add($data);
    }
}
