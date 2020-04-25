<?php

namespace App\Policies;

use App\User;
use App\Game;
use Illuminate\Auth\Access\HandlesAuthorization;

class GamePolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can create games.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_super_admin == 1 ? true : false;
    }

    /**
     * Determine whether the user can update the game.
     *
     * @param  \App\User  $user
     * @param  \App\Game  $game
     * @return mixed
     */
    public function update(User $user, Game $game)
    {
        return $user->is_super_admin == 1 ? true : false;
    }

    /**
     * Determine whether the user can delete the game.
     *
     * @param  \App\User  $user
     * @param  \App\Game  $game
     * @return mixed
     */
    public function delete(User $user, Game $game)
    {
        return $user->is_super_admin == 1 ? true : false;
    }

}
