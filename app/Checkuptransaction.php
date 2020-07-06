<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkuptransaction extends Model
{
    //
    public function transaction(){
        return $this->hasOne('App\Transaction');
    }

    public function patientcheckup(){
        return $this->hasOne('App\Patientcheckup');
    }
}
