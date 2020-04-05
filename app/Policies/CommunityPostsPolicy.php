<?php

namespace App\Policies;

use App\Community;
use App\CommunitySubscriber;
use App\User;
use App\oneHasManyModels\CommunityPosts;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunityPostsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any community posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the community posts.
     *
     * @param  \App\User  $user
     * @param  \App\oneHasManyModels\CommunityPosts  $communityPosts
     * @return mixed
     */
    public function view(User $user, CommunityPosts $communityPosts)
    {
        //
    }

    /**
     * Determine whether the user can create community posts.
     *
     * @param \App\User $user
     * @param Community $community
     * @return mixed
     */
    public function create(User $user,Community $community)
    {
        $subscriber = CommunitySubscriber::where([
            ['user_id',$user->id],
            ['community_id',$community->id],
            ['is_moderator',1],
        ])->first();
        return isset($subscriber) ? true : false;
    }

    /**
     * Determine whether the user can update the community posts.
     *
     * @param  \App\User  $user
     * @param  \App\oneHasManyModels\CommunityPosts  $communityPosts
     * @return mixed
     */
    public function update(User $user, CommunityPosts $communityPosts)
    {
        //
    }

    /**
     * Determine whether the user can delete the community posts.
     *
     * @param  \App\User  $user
     * @param  \App\oneHasManyModels\CommunityPosts  $communityPosts
     * @return mixed
     */
    public function delete(User $user, CommunityPosts $communityPosts)
    {
        //
    }

    /**
     * Determine whether the user can restore the community posts.
     *
     * @param  \App\User  $user
     * @param  \App\oneHasManyModels\CommunityPosts  $communityPosts
     * @return mixed
     */
    public function restore(User $user, CommunityPosts $communityPosts)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the community posts.
     *
     * @param  \App\User  $user
     * @param  \App\oneHasManyModels\CommunityPosts  $communityPosts
     * @return mixed
     */
    public function forceDelete(User $user, CommunityPosts $communityPosts)
    {
        //
    }
}
