<?php

namespace App\Policies;

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
     * @param User $ownerOfWall
     * @return mixed
     */
    public function postToWall(User $user,User $ownerOfWall)
    {
        if(($ownerOfWall->wall_can_edit == 0 || $ownerOfWall->wall_can_edit == 1) && $ownerOfWall->id == $user->id){
            return true;
        }elseif($ownerOfWall->wall_can_edit == 1 && $ownerOfWall->isFriend($user->id)){
            return true;
        }elseif($ownerOfWall->wall_can_edit == 2){
            return true;
        }else{
            return false;
        }
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
     * @param User $user
     * @param \App\Post $post
     * @return mixed
     */
    public function delete(User $user, Post $post)
    {
        return $post->recipient->id == $user->id || $post->writer->id == $user->id ? true : false;
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
