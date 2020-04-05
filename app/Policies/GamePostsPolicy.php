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
     * Determine whether the user can view any game posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the game posts.
     *
     * @param  \App\User  $user
     * @param  \App\GamePosts  $gamePosts
     * @return mixed
     */
    public function view(User $user, GamePosts $gamePosts)
    {
        //
    }

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

    /**
     * Determine whether the user can update the game posts.
     *
     * @param  \App\User  $user
     * @param  \App\GamePosts  $gamePosts
     * @return mixed
     */
    public function update(User $user, GamePosts $gamePosts)
    {
        //
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param \App\User $user
     * @param Game $game

     * @return mixed
     */
    public function delete(User $user,Game $game )
    {
    }
    /**
     * Determine whether the user can restore the game posts.
     *
     * @param  \App\User  $user
     * @param  \App\GamePosts  $gamePosts
     * @return mixed
     */
    public function restore(User $user, GamePosts $gamePosts)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the game posts.
     *
     * @param  \App\User  $user
     * @param  \App\GamePosts  $gamePosts
     * @return mixed
     */
    public function forceDelete(User $user, GamePosts $gamePosts)
    {
        //
    }
}
