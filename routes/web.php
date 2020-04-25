<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'verified'], function () {
    // PageController ///////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////

    // ProfileController ///////////////////////////////////////////////////////////////
    Route::get('/', 'ProfileController@index');

    Route::get('/profile/id/{id}', 'ProfileController@profile')->name('profile');
    Route::get('/edit', 'ProfileController@edit')->name('edit');
    Route::put('/edit', 'ProfileController@update')->name('update');

    Route::prefix('settings')->group(function (){
        Route::get('/', 'ProfileController@settings')->name('settings');
        Route::put('/', 'ProfileController@updateSettings')->name('settings');
    });

    Route::prefix('email')->group(function (){
        Route::get('/edit', 'ProfileController@editEmail')->name('editEmail');
        Route::put('/edit', 'ProfileController@updateEmail')->name('updateEmail');
    });
    Route::prefix('password')->group(function (){
        Route::get('/edit', 'ProfileController@editPassword')->name('editPassword');
        Route::put('/edit', 'ProfileController@updatePassword')->name('updatePassword');
    });
    Route::post('/update-photo', 'ProfileController@updatePhoto')->name('updatePhoto');
    ////////////////////////////////////////////////////////////////////////////////////

    // ProfileCommentController //////////////////////////////////////////////////////////////////
    Route::resource('profile/comment','ProfileCommentController',['only'=>['store','destroy']]);
    ////////////////////////////////////////////////////////////////////////////////////

    // FriendShipController //////////////////////////////////////////////////////////////////
    Route::prefix('friends')->group(function (){
        Route::get('/all', 'FriendShipController@index')->name('friendsAll');
        Route::get('/all/id={id}', 'FriendShipController@friendsById')->name('friendsById');
        Route::post('/add','FriendShipController@addFriend')->name('addFriend');
        Route::get('/online', 'FriendShipController@friendsOnline')->name('friendsOnline');
        Route::get('/online/id={id}', 'FriendShipController@friendsOnlineById')->name('friendsOnlineById');
        Route::get('/new', 'FriendShipController@friendsNew')->name('friendsNew');
        Route::get('/requested', 'FriendShipController@friendsRequestSent')->name('friendsRequestSent');
        Route::get('/mutual/id={id}', 'FriendShipController@mutualFriends')->name('mutualFriends');
        Route::put('/accept', 'FriendShipController@friendAccept')->name('friendAccept');
        Route::delete('/delete/{id}', 'FriendShipController@deleteFriend')->name('deleteFriend');
    });
    ////////////////////////////////////////////////////////////////////////////////////



    // GameController //////////////////////////////////////////////////////////////////
    Route::get('/games/all', 'GameController@index')->name('gamesAll');

    Route::prefix('game')->group(function (){

        Route::get('/{game}','GameController@show')->name('game.show');

        Route::middleware('can:create,App\Game')->group(function (){
            Route::get('/create/new','GameController@create')->name('game.create');
            Route::post('/','GameController@store')->name('game.store');
        });
        Route::middleware('can:update,game')->group(function (){
            Route::get('/{game}/edit','GameController@edit')->name('game.edit');
            Route::put('/{game}','GameController@update')->name('game.update');
            Route::delete('/{game}','GameController@destroy')->name('game.destroy');
        });

    });
//    Route::resource('game','GameController',['only'=>['update','destroy']]);
    ////////////////////////////////////////////////////////////////////////////////////

    // GameSubscriberController ////////////////////////////////////////////////////////
    Route::namespace('manyToMany')->group(function (){
        Route::get('/games/subscriptions', 'GameSubscriberController@index')->name('gamesSubscriptions');
        Route::get('/games/subscriptions/id={id}', 'GameSubscriberController@gamesSubscriptionsById')->name('gamesSubscriptionsById');

        Route::post('game/subscriber','GameSubscriberController@store')->name('subscriber.store');
        Route::delete('game/subscriber/{game}','GameSubscriberController@destroy')->name('subscriber.destroy');

        Route::get('game/subscribers/{game}','GameSubscriberController@allGameSubscribers')->name('allGameSubscribers');
        Route::get('game/subscribers/{game}/online','GameSubscriberController@onlineSubscribers')->name('onlineSubscribers');
        Route::get('game/subscribers/{game}/friends','GameSubscriberController@gameSubscribersFriends')->name('gameSubscribersFriends');

        Route::put('/game/add/moderator/{gameSubscriber}', 'GameSubscriberController@addModerator')->name('game.addModerator')->middleware('can:isSuperAdmin');
        Route::put('/game/remove/moderator/{gameSubscriber}', 'GameSubscriberController@removeModerator')->name('game.removeModerator')->middleware('can:isSuperAdmin');


        // community
        Route::get('/communities/subscriptions', 'CommunitySubscriberController@index')->name('community.my.subscriptions');
        Route::get('/communities/my', 'CommunitySubscriberController@myCommunities')->name('community.my');
        Route::get('/communities/subscriptions/id={id}', 'CommunitySubscriberController@communitiesSubscriptionsById')->name('communitiesSubscriptionsById');

        Route::post('community/subscriber','CommunitySubscriberController@store')->name('community.sub.store');
        Route::delete('community/subscriber/{community}','CommunitySubscriberController@destroy')->name('community.sub.destroy');

        Route::get('community/subscribers/{community}','CommunitySubscriberController@allCommunitySubscribers')->name('allCommunitySubscribers');
        Route::get('community/subscribers/{community}/online','CommunitySubscriberController@communityOnlineSubscribers')->name('communityOnlineSubscribers');
        Route::get('community/subscribers/{community}/friends','CommunitySubscriberController@communitySubscribersFriends')->name('communitySubscribersFriends');

        Route::put('/community/add/moderator/{communitySubscriber}', 'CommunitySubscriberController@addModerator')->name('community.addModerator');
        Route::put('/community/remove/moderator/{communitySubscriber}', 'CommunitySubscriberController@removeModerator')->name('community.removeModerator');

        Route::put('/community/add/admin/{communitySubscriber}', 'CommunitySubscriberController@addAdmin')->name('community.addAdmin');
        Route::put('/community/remove/admin/{communitySubscriber}', 'CommunitySubscriberController@removeAdmin')->name('community.removeAdmin');


    });
    ////////////////////////////////////////////////////////////////////////////////////

    // PostController ////////////////////////////////////////////////////////


    Route::namespace('oneHasMany')->group(function (){
        // GamePost
        Route::delete('post/game/{post}/{game}','GamePostController@destroy')->name('game.post.destroy');
        Route::post('post/game','GamePostController@store')->name('game.post.store');
        //CommunityPost
        Route::delete('post/community/{post}/{community}','CommunityPostController@destroy')->name('community.post.destroy');
        Route::post('post/community','CommunityPostController@store')->name('community.post.store');
    });

    ////////////////////////////////////////////////////////////////////////////////////

    // CommunityController ////////////////////////////////////////////////////////
    Route::get('communities/all','CommunityController@index')->name('community.all');
    Route::get('community/{short_address}','CommunityController@show')->name('community.show');

    Route::get('community/create/new','CommunityController@create')->name('community.create');
    Route::post('community/store','CommunityController@store')->name('community.store');

    Route::get('community/edit/{community}','CommunityController@edit')->name('community.edit');
    Route::put('community/update/{community}','CommunityController@update')->name('community.update');

    Route::delete('community/{community}','CommunityController@destroy')->name('community.destroy');


});
Auth::routes(['verify' => true]);
