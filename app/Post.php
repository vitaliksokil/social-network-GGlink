<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'post','recipient_id','writer_id'
    ];

    public function recipient(){
        return $this->belongsTo(User::class);
    }
    public function writer(){
        return $this->belongsTo(User::class);
    }
}
