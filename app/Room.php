<?php

namespace App;

use App\oneHasManyModels\RoomMember;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded=[];

    public function game(){
        return $this->belongsTo(Game::class);
    }
    public function creator(){
        return $this->belongsTo(User::class);
    }
    public function members(){
        return $this->hasMany(RoomMember::class)->select(['member_id','is_joined'])->with('member');
    }
}
