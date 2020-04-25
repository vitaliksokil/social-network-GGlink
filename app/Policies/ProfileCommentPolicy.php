<?php

namespace App\Policies;

use App\User;
use App\ProfileComment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfileCommentPolicy
{
    use HandlesAuthorization;

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
     * Determine whether the user can delete the post.
     *
     * @param User $user
     * @param \App\ProfileComment $comment
     * @return mixed
     */
    public function delete(User $user, ProfileComment $comment)
    {
        return $comment->recipient->id == $user->id || $comment->writer->id == $user->id ? true : false;
    }
}
