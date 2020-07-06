<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctorappointment extends Model
{
    //
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function patientcheckup(){
        return $this->hasOne('App\Patientcheckup');
    }
}
