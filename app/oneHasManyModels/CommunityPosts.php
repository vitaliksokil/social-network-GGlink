<?php

namespace App\oneHasManyModels;

use App\Community;
use App\Post;
use Illuminate\Database\Eloquent\Model;

class CommunityPosts extends Model
{
    protected $fillable=[
        'community_id',
        'post_id',
    ];

    public function community(){
        return $this->belongsTo(Community::class,'community_id');
    }
    public function post(){
        return $this->belongsTo(Post::class,'post_id');
    }
}
