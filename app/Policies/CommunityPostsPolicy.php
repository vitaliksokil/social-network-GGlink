<?php

namespace App\Policies;

use App\Community;
use App\CommunitySubscriber;
use App\User;
use App\oneHasManyModels\CommunityPosts;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunityPostsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create community posts.
     *
     * @param \App\User $user
     * @param Community $community
     * @return mixed
     */
    public function create(User $user,Community $community)
    {
        $subscriber = CommunitySubscriber::where([
            ['user_id',$user->id],
            ['community_id',$community->id],
            ['is_moderator',1],
        ])->first();
        return isset($subscriber) ? true : false;
    }

}
