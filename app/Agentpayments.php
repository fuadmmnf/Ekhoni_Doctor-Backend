<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentpayments extends Model
{
    use HasFactory;

    public function agent(){
        return $this->belongsTo('App\User');
    }
}
