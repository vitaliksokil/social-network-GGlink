<?php

namespace App\manyToManyModels;

use App\Community;
use App\User;
use Illuminate\Database\Eloquent\Model;

class CommunitySubscriber extends Model
{
    protected $fillable =[
      'user_id','community_id','is_moderator','is_admin','is_creator'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function community(){
        return $this->belongsTo(Community::class);
    }
}
