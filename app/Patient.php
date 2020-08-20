<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function patientcheckups(){
        return $this->hasMany('App\Patientcheckup');
    }

    public function patientprescriptions(){
        return $this->hasMany('App\Patientprescription');
    }
}
