<?php

namespace App;

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

}
