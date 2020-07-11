<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function patientcheckup(){
        return $this->hasOne('App\Patientcheckup');
    }
}
