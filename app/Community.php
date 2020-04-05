<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = [
        'game_id',
        'title',
        'short_address',
        'info',
        'logo',
        'poster'
    ];

    public function subscribers(){
        return $this->hasMany(CommunitySubscriber::class,'community_id')->with('user');
    }
//    public function posts(){
//        return $this->hasMany(CommunityPosts::class,'game_id')->with('post');
//    }
}
