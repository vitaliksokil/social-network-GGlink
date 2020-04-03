<?php

namespace App\Policies;

use App\Game;
use App\GameSubscriber;
use App\User;
use App\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function view(User $user, Post $post)
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
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param \App\User $user
     * @param Game $game
     * @param \App\Post $post
     * @return mixed
     */
    public function delete(User $user,Post $post,Game $game )
    {
        $subscriber = GameSubscriber::where([
            ['user_id',$user->id],
            ['game_id',$game->id],
            ['is_moderator',1],
        ])->first();
        return isset($subscriber) ? true : false;
    }

    /**
     * Determine whether the user can restore the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function restore(User $user, Post $post)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function forceDelete(User $user, Post $post)
    {
        //
    }
}
