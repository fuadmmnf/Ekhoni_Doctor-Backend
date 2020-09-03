<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Doctorappointment extends Model
{

    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function patient(){
        return $this->hasOneThrough('App\Patient', 'App\Patientcheckup');
    }

    public function patientcheckup(){
        return $this->belongsTo('App\Patientcheckup');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
