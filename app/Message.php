<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded =[];

    public function to_user(){
        return $this->belongsTo(User::class,'to');
    }

    public function from_user(){
        return $this->belongsTo(User::class,'from');
    }
}
