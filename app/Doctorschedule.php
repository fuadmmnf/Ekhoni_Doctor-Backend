<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Doctorschedule extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    public function freerequests(){
        return $this->hasMany('App\Freerequest');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
