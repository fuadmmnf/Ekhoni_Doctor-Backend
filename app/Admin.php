<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'email', 'password',
    ];

    protected $hidden = [
        'password',
    ];


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
