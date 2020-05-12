<?php

namespace App\Policies;

use App\Game;
use App\GameSubscriber;
use App\Post;
use App\User;
use App\GamePosts;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePostsPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can create posts.
     *
     * @param \App\User $user
     * @param Game $game
     * @return mixed
     */
    public function create(User $user,Game $game)
    {
        $subscriber = GameSubscriber::where([
            ['user_id',$user->id],
            ['game_id',$game->id],
            ['is_moderator',1],
        ])->first();
        return isset($subscriber) ? true : false;
    }

}
