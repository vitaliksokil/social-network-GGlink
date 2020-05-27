<?php

namespace App\Providers;

use App\Message;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\ProfileComment' => 'App\Policies\ProfileCommentPolicy',
         'App\Game' => 'App\Policies\GamePolicy',
         'App\manyToManyModels\GameSubscriber' => 'App\Policies\GameSubscriberPolicy',
         'App\Post' => 'App\Policies\PostPolicy',
         'App\oneHasManyModels\GamePosts' => 'App\Policies\GamePostsPolicy',
         'App\oneHasManyModels\CommunityPosts' => 'App\Policies\CommunityPostsPolicy',
         'App\manyToManyModels\CommunitySubscriber' => 'App\Policies\CommunitySubscriberPolicy',
         'App\Message' => 'App\Policies\MessagePolicy',
         'App\oneHasManyModels\RoomMember' => 'App\Policies\RoomMemberPolicy',
         'App\Room' => 'App\Policies\RoomPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSuperAdmin', function (User $user) {
            return $user->is_super_admin ? true : false;
        });

        Gate::define('isConversationExist', function ($authUser, $firstUserId, $secondUserId) {
            $messages = Message::where(function($q) use($firstUserId,$secondUserId){
                $q->where([
                    ['from',$firstUserId],
                    ['to',$secondUserId],
                ])->orWhere([
                    ['from',$secondUserId],
                    ['to',$firstUserId],
                ]);
            })->orderBy('created_at','asc')->get();
            return $messages->isNotEmpty();
        });
        //
    }
}
