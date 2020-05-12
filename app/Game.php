<?php

namespace App;

use App\oneHasManyModels\GamePosts;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable=[
        'title','short_address','info','logo','poster'
    ];

    public function subscribers(){
        return $this->hasMany(GameSubscriber::class,'game_id')->with('user');
    }
    public function posts(){
        return $this->hasMany(GamePosts::class,'game_id')->with('post');
    }
}
