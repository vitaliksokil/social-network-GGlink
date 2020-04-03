<?php

namespace App\Policies;

use App\Game;
use App\User;
use App\GameSubscriber;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameSubscriberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any game subscribers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the game subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\GameSubscriber  $gameSubscriber
     * @return mixed
     */
    public function view(User $user, GameSubscriber $gameSubscriber)
    {
        //
    }

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

    /**
     * Determine whether the user can update the game subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\GameSubscriber  $gameSubscriber
     * @return mixed
     */
    public function update(User $user, GameSubscriber $gameSubscriber)
    {
        //
    }

    /**
     * Determine whether the user can delete the game subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\GameSubscriber  $gameSubscriber
     * @return mixed
     */
    public function delete(User $user, GameSubscriber $gameSubscriber)
    {
        //
    }

    /**
     * Determine whether the user can restore the game subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\GameSubscriber  $gameSubscriber
     * @return mixed
     */
    public function restore(User $user, GameSubscriber $gameSubscriber)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the game subscriber.
     *
     * @param  \App\User  $user
     * @param  \App\GameSubscriber  $gameSubscriber
     * @return mixed
     */
    public function forceDelete(User $user, GameSubscriber $gameSubscriber)
    {
        //
    }
}
