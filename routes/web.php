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

    });
    ////////////////////////////////////////////////////////////////////////////////////

    // PostController ////////////////////////////////////////////////////////
    Route::post('post','PostController@store')->name('post.store');
    Route::delete('post/{post}/game/{game}','PostController@destroy')->name('post.destroy');

    ////////////////////////////////////////////////////////////////////////////////////

});
Auth::routes(['verify' => true]);
