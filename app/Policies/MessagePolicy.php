<?php

namespace App\Policies;

use App\User;
use App\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function doesConversationExist(User $authUser,$userID2){
        $authUserId = $authUser->id;
        $messages = Message::where(function($q) use($authUserId,$userID2){
            $q->where([
                ['from',$authUserId],
                ['to',$userID2],
            ])->orWhere([
                ['from',$userID2],
                ['to',$authUserId],
            ]);
        })->orderBy('created_at','asc')->get();
        return $messages->isNotEmpty();
    }

    public function sendTo(User $authUser,User $user){
        if($user->message_can_send == 0){
            return false;
        }elseif($user->message_can_send == 1){
            // check if it's a friend
            return $user->isFriend($authUser->id);
        }else{
            return true;
        }
    }
}
