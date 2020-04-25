<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FriendShip extends Model
{
    protected $fillable = [
      'sender_id','receiver_id','status'
    ];
    protected $hidden = [
      'created_at','updated_at'
    ];

    public function sender(){
        return $this->belongsTo(User::class,'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id');
    }
    static public function findFriendShip($first_id,$second_id):Builder{
        return self::where(function($query) use ($first_id,$second_id) {
            $query->where([
                ['receiver_id',$first_id],
                ['sender_id',$second_id],
            ])->orWhere([
                ['receiver_id',$second_id],
                ['sender_id',$first_id],
             ]);
        });
    }
}
