<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Doctorschedule extends Model
{
    public function doctor(){
        return $this->belongsTo('App\Doctor');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }


}
