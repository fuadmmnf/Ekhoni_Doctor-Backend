<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkupprescription extends Model
{
    //
    public function patientcheckup(){
        return $this->belongsTo('App\Patientcheckup');
    }
}
