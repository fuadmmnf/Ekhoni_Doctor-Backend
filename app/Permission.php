<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    public function admins(){
        return $this->belongsToMany('App\Admin');
    }
}
