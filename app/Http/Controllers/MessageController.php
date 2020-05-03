<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\Notifications\NewMessageNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function myMessagesPage()
    {
        return view('pages.messages.my');
    }

    public function getMyMessages()
    {
        // todo don;t allow to see /get/messages page.
        $my_id = Auth::user()->id;
        $messages = Message::with([
            'to_user' => function ($q) {
                $q->select('id', 'name','nickname','surname','photo');
            },
            'from_user'=>function($q){
                $q->select('id', 'name','nickname','surname','photo');
            },
        ])
            ->where('to', $my_id)
            ->orWhere('from', $my_id)
            ->orderBy('created_at','desc')
            ->get()
            ->unique(function ($item) {
                return $item['to'] . $item['from'];
            });
        $usersWithWhomIgetConversation = [];
        // get users with whom i get conversation
        foreach ($messages as $message) {
            if ($message['to'] == $my_id) {
                $usersWithWhomIgetConversation[] = $message['from_user'];
            } else {
                $usersWithWhomIgetConversation[] = $message['to_user'];
            }
        }
        // unique these users, because there possible duplicates
        $usersWithWhomIgetConversation = array_unique($usersWithWhomIgetConversation);
        // getting is that user online or not + last message
        foreach ($usersWithWhomIgetConversation as $user){
            $user->isOnline = $user->isOnline();
            $user->lastMessage = Message::where('to',$user->id)->orWhere('from',$user->id)->orderBy('created_at','desc')->first();;
            $user->unreadMessagesCount = Message::select('from','to','is_read')->where([
                ['from',$user->id],
                ['to',Auth::user()->id],
                ['is_read',false],
            ])->count();

        }
        return response()->json($usersWithWhomIgetConversation);
    }
    public function getMessagesFor($userNickname,$userId){
        // todo check if user can get that conversation, check by userID

        // setting all messages as read
        Message::where([
            ['from',$userId],
            ['to',Auth::user()->id],
            ['is_read',false]
        ])->update(['is_read'=>true]);

        $myId = Auth::user()->id;
        $messages = Message::where(function($q) use($myId,$userId){
            $q->where([
                ['from',$myId],
                ['to',$userId],
            ])->orWhere([
                ['from',$userId],
                ['to',$myId],
            ]);
        })->orderBy('created_at','asc')->get();
        // stacking messages of 1 user to 1 array,
        // new array
        // ( from 1, to 2, messages = [message1,message2], from 2 to 1, messages = [message1,message2],from 1, to 2, messages = [message1,message2]...)
        $newMessagesCollection = new Collection();
        $currentFrom = $messages->first()->from;
        $currentTo = $messages->first()->to;
        $currentMessages = [];
        foreach ($messages as $message){
            if($currentFrom == $message->from){
                $currentMessages[] = [
                    'is_read'=>$message->is_read,
                    'text'=>$message->text,
                    'created_at'=>(string)$message->created_at];
            }else{
                $newMessagesCollection->push([
                    'from'=>$currentFrom,
                    'to'=>$currentTo,
                    'messages' => $currentMessages
                ]);
                $currentFrom = $message->from;
                $currentTo = $message->to;
                $currentMessages = [];
                $currentMessages[] = [
                    'is_read'=>$message->is_read,
                    'text'=>$message->text,
                    'created_at'=>(string)$message->created_at];
            }
        }
        // for last element, because we skip it, by continue
        // ( i could check if it's not last element then continue, but i think it will be more expensive for productivity,
        // because if we would got 2millions messages checking every time will be very costly)
        // we have 1 more stack of messages, which will not pass through else section previously in loop
        // so i decided to do some actions for last stack of messages, because we need to write it
        $lastMessage = $newMessagesCollection->pop(); // firstly getting the last element
        if($lastMessage['from'] == $currentFrom){         // checking if sender id of last messages in ready collection is the same as it's in the last stack of messages
            foreach ($currentMessages as $currentMessage){  // if so, the whole array we push to messages array of last item of collection
                $lastMessage['messages'][] = $currentMessage;
            }
            $newMessagesCollection->push($lastMessage); // pushing the element back to the collection, because we popped it
        }else{
            // if sender id is not the same, it means that we have another new stack of messages from another user
            $newMessagesCollection->push($lastMessage); // pushing the last element back to collection, because we popped it
            // adding a new stack of messages from another user
            $newMessagesCollection->push([
                'from' =>$currentFrom,
                'to' => $currentTo,
                'messages'=>$currentMessages
            ]);
        }

        return view('pages.messages.conversation',[
            'messages'=>$newMessagesCollection,
            'userConversationWith'=>User::findOrFail($userId)
        ]);

    }
    public function sendMessage(Request $request){
        try {
            $newMessage = Message::create([
                'from' => Auth::user()->id,
                'to' => $request->to,
                'text' => $request->text
            ]);
            broadcast(new NewMessage($newMessage));

            $newMessage->to_user->notify(new NewMessageNotification([
                'message'=>$newMessage,
                'from_user'=>$newMessage->from_user,
                'newMessagesCount'=>count($newMessage->to_user->newMessages())]));

            return response()->json($newMessage);
        }catch (\Exception $e){

            abort(500,$e->getMessage());
        }
    }
}
