<?php

namespace App\Policies;

use App\Community;
use App\Game;
use App\User;
use App\CommunitySubscriber;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommunitySubscriberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any community subscribers.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the community subscriber.
     *
     * @param \App\User $user
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return mixed
     */
    public function view(User $user, CommunitySubscriber $communitySubscriber)
    {
        //
    }

    /**
     * Determine whether the user can create community subscribers.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the community subscriber.
     *
     * @param \App\User $user
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return mixed
     */
    public function update(User $user, CommunitySubscriber $communitySubscriber)
    {

    }

    /**
     * Determine whether the user can delete the community subscriber.
     *
     * @param \App\User $user
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return mixed
     */
    public function delete(User $user, CommunitySubscriber $communitySubscriber)
    {
        //
    }

    /**
     * Determine whether the user can restore the community subscriber.
     *
     * @param \App\User $user
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return mixed
     */
    public function restore(User $user, CommunitySubscriber $communitySubscriber)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the community subscriber.
     *
     * @param \App\User $user
     * @param \App\CommunitySubscriber $communitySubscriber
     * @return mixed
     */
    public function forceDelete(User $user, CommunitySubscriber $communitySubscriber)
    {
        //
    }

    public function subscribe(User $user, Community $community)
    {
        return CommunitySubscriber::where([['user_id', $user->id], ['community_id', $community->id]])->first() ? false : true;
    }

    public function isAdmin(User $user, Community $community)
    {
        $sub = CommunitySubscriber::where([
            ['user_id', $user->id],
            ['community_id', $community->id],
            ['is_admin',1]
        ])->first();
        return isset($sub) ? true : false;
    }

    public function isCreator(User $user, Community $community)
    {
        $sub = CommunitySubscriber::where([
            ['user_id', $user->id],
            ['community_id', $community->id],
            ['is_creator',1]

        ])->first();
        return isset($sub) ? true : false;
    }
}
