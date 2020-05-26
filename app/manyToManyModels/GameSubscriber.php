<?php

namespace App\manyToManyModels;

use App\Game;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GameSubscriber extends Model
{
    protected $fillable=[
        'user_id','game_id','is_moderator'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function game(){
        return $this->belongsTo(Game::class);
    }

}
