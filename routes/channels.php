<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('messages.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('typing.{firstUserId}.{secondUserId}', function ($user,$firstUserId,$secondUserId) {
    // creating channel for 2 id's to be unique!!!
    // so user is listen for 'typing.{hisID}.{userConversationWithID}'
    // and sending to 'typing.{userConversationWithID}.{hisID}',
    // so for example user with id 1 is chatting with user with id 2,
    // first user sends to typing.2.1 channel and listening to typing.1.2 channel
    // second one sends to typing.1.2 channel and listening to typing 2.1 channel
    if(
        ($user->id == $firstUserId || $user->id == $secondUserId)
        &&
        \Illuminate\Support\Facades\Gate::allows('isConversationExist',[$firstUserId,$secondUserId])
    )
    {
        return true;
    }
});
Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('rooms.of.game.{game_short_address}', function () {
    return true;
});

Broadcast::channel('room.{id}', function () {
    return true;
});

