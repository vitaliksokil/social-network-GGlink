<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfileComment extends Model
{
    protected $fillable = [
        'comment','recipient_id','writer_id'
    ];

    public function recipient(){
        return $this->belongsTo(User::class);
    }
    public function writer(){
        return $this->belongsTo(User::class);
    }
}
