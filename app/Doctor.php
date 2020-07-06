<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'mobile', 'bmdc_number', 'email', 'password',
    ];
    protected $hidden = [
        'password',
    ];

    public function doctortype(){
        return $this->belongsTo('App\Doctortype');
    }

    public function patientcheckups(){
        return $this->hasMany('App\Patientcheckup');
    }

    public function doctorappointments(){
        return $this->hasMany('App\Doctorappointment');
    }
}
