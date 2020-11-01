<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freerequest extends Model
{
    use HasFactory;

    public function doctorschedule(){
        return $this->belongsTo('App\Doctorschedule');
    }
    public function patient(){
        return $this->belongsTo('App\Patient');
    }
}
