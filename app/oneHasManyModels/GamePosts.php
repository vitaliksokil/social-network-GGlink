<?php

namespace App\oneHasManyModels;

use App\Game;
use App\Post;
use Illuminate\Database\Eloquent\Model;

class GamePosts extends Model
{
    protected $fillable=[
      'game_id',
      'post_id',
    ];

    public function game(){
        return $this->belongsTo(Game::class,'game_id');
    }
    public function post(){
        return $this->belongsTo(Post::class,'post_id');
    }
}
