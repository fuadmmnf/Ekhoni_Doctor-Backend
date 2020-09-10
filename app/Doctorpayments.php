<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctorpayments extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }
}
