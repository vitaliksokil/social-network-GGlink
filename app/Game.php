<?php

namespace App;

use App\manyToManyModels\GameSubscriber;
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
    public function rooms(){
        return $this->hasMany(Room::class,'game_id');
    }
    public function unlockedRooms(){
        return $this->hasMany(Room::class,'game_id')->where('is_locked',0);
    }
}
