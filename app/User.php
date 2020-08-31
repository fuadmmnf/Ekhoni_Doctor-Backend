<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile', 'device_ids', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];


    /**
     * Specifies the user's FCM token
     *
     * @return string
     */
    public function routeNotificationForFcm()
    {
        return json_decode($this->device_ids, true);
    }


    public function admin(){
        return $this->hasOne('App\Admin');
    }

    public function doctor(){
        return $this->hasOne('App\Doctor');
    }


    public function patients(){
        return $this->hasMany('App\Patient');
    }


//    public function isSuperAdmin(){
//        return $this->permissions()->count() == Permission::count();
//    }


}
