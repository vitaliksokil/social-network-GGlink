<?php

namespace App\Providers;

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
         'App\GameSubscriber' => 'App\Policies\GameSubscriberPolicy',
         'App\Post' => 'App\Policies\PostPolicy',
         'App\oneHasManyModels\GamePosts' => 'App\Policies\GamePostsPolicy',
         'App\oneHasManyModels\CommunityPosts' => 'App\Policies\CommunityPostsPolicy',
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
        //
    }
}
