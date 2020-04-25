<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function myMessagesPage()
    {
        return view('pages.messages.my');
    }

    public function getMyMessages()
    {
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
        // todo + last message
        // unique these users, because there possible duplicates
        $usersWithWhomIgetConversation = array_unique($usersWithWhomIgetConversation);
        // getting is that user online or not + last message
        foreach ($usersWithWhomIgetConversation as $user){
            $user->isOnline = $user->isOnline();
            $user->lastMessage = Message::where('to',$user->id)->orWhere('from',$user->id)->orderBy('created_at','desc')->first();
        }
        return response()->json($usersWithWhomIgetConversation);

    }
}
