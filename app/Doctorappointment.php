<?php

namespace App;

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
}
