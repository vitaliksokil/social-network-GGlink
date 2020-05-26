<?php

namespace App\Policies;

use App\Community;
use App\Game;
use App\User;
use App\manyToManyModels\CommunitySubscriber;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunitySubscriberPolicy
{
    use HandlesAuthorization;

    public function subscribe(User $user, Community $community)
    {
        return CommunitySubscriber::where([['user_id', $user->id], ['community_id', $community->id]])->first() ? false : true;
    }

    public function isAdmin(User $user, Community $community)
    {
        $sub = CommunitySubscriber::where([
            ['user_id', $user->id],
            ['community_id', $community->id],
            ['is_admin',1]
        ])->first();
        return isset($sub) ? true : false;
    }

    public function isCreator(User $user, Community $community)
    {
        $sub = CommunitySubscriber::where([
            ['user_id', $user->id],
            ['community_id', $community->id],
            ['is_creator',1]

        ])->first();
        return isset($sub) ? true : false;
    }
    public function update(User $user,CommunitySubscriber $communitySubscriber)
    {
        return $communitySubscriber->is_creator != 1 ? true : false;
    }
}
