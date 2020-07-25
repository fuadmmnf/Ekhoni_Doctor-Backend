<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctorschedule extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }
}
