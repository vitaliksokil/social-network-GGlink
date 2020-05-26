<?php

namespace App\oneHasManyModels;

use App\Room;
use App\User;
use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model
{
    protected $guarded = [];

    protected $hidden =[
        'created_at','updated_at'
    ];


    public function member(){
        return $this->belongsTo(User::class,'member_id')->select(['id','name','nickname','surname','photo']);
    }
    public function room(){
        return $this->belongsTo(Room::class,'room_id');
    }
}
