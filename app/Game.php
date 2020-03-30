<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable=[
        'title','short_address','info','logo','poster'
    ];
}
