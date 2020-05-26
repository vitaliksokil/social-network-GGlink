<?php

namespace App\Policies;

use App\Community;
use App\manyToManyModels\CommunitySubscriber;
use App\Game;
use App\manyToManyModels\GameSubscriber;
use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can delete the post.
     *
     * @param \App\User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function delete(User $user,Post $post,$gameOrCommunity)
    {
        if($gameOrCommunity instanceof Game){
            $game = $gameOrCommunity;
            $subscriber = GameSubscriber::where([
                ['user_id',$user->id],
                ['game_id',$game->id],
                ['is_moderator',1],
            ])->first();
            return isset($subscriber) ? true : false;
        }elseif ($gameOrCommunity instanceof Community){
            $community = $gameOrCommunity;
            $subscriber = CommunitySubscriber::where([
                ['user_id',$user->id],
                ['community_id',$community->id],
                ['is_moderator',1],
            ])->first();
            return isset($subscriber) ? true : false;
        }

    }
}
