<?php

namespace App\Policies;

use App\Game;
use App\User;
use App\manyToManyModels\GameSubscriber;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameSubscriberPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can create game subscribers.
     *
     * @param \App\User $user
     * @param Game $game
     * @return mixed
     */
    public function subscribe(User $user,Game $game)
    {
        return GameSubscriber::where([['user_id',$user->id],['game_id',$game->id]])->first() ? false : true;
    }

}
