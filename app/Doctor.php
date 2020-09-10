<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Model
{
    use Notifiable;

    protected $fillable = [
        'name', 'mobile', 'bmdc_number', 'email', 'password',
    ];
    protected $hidden = [
        'password',
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function doctortype(){
        return $this->belongsTo('App\Doctortype');
    }

    public function patientcheckups(){
        return $this->hasMany('App\Patientcheckup');
    }

    public function doctorappointments(){
        return $this->hasMany('App\Doctorappointment');
    }

    public  function doctorpayments(){
        return $this->hasMany('App\Doctorpayments');
    }
}
