<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    public function admin(){
        return $this->hasOne('App\Admin');
    }

    public function doctor(){
        return $this->hasOne('App\Doctor');
    }


    public function patients(){
        return $this->hasMany('App\Patient');
    }



    public function permissions(){
        return $this->belongsToMany('App\Permission');
    }

    public function isSuperAdmin(){
        return $this->permissions()->count() == Permission::count();
    }

    public function hasPermission($permission){
        foreach ($this->permissions() as $perm){
            if($perm->name == $permission->name)
                return true;
        }
        return false;
    }
}
