<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Patientcheckup extends Model
{
    protected $hidden = [
        'transaction_id',
    ];

    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function patient(){
        return $this->belongsTo('App\Patient');
    }

    public function transaction(){
        return $this->belongsTo('App\Transaction');
    }

//    public function doctorappointment(){
//        return $this->be('App\Doctorappointment');
//    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
