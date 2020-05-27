<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Message;
use App\Notifications\MessagesHaveBeenRead;
use App\Notifications\NewMessageNotification;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function myMessagesPage(Request $request)
    {
        $messages = $this->getMyMessages();
        if($request->ajax()){
            if($search = \Request::get('q')){
                $messages = array_filter($messages,function ($item) use ($search) {
                    if (
                        stristr($item->id, $search)
                        ||
                        stristr($item->name, $search)
                        ||
                        stristr($item->nickname, $search)
                        ||
                        stristr($item->surname, $search)
                    ) {
                        return $item;
                    }
                });
            }
            return $messages;
        }
        return view('pages.messages.my', [
            'messages' => $messages
        ]);
    }

    private function getMyMessages()
    {
        $my_id = Auth::user()->id;
        $messages = Message::with([
            'to_user' => function ($q) {
                $q->select('id', 'name', 'nickname', 'surname', 'photo');
            },
            'from_user' => function ($q) {
                $q->select('id', 'name', 'nickname', 'surname', 'photo');
            },
        ])
            ->where('to', $my_id)
            ->orWhere('from', $my_id)
            ->orderBy('created_at', 'desc')
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
        foreach ($usersWithWhomIgetConversation as $user) {
            $user->isOnline = $user->isOnline();
            $user->lastMessage = Message::where('to', $user->id)->orWhere('from', $user->id)->orderBy('created_at', 'desc')->first();;
            $user->unreadMessagesCount = Message::select('from', 'to', 'is_read')->where([
                ['from', $user->id],
                ['to', Auth::user()->id],
                ['is_read', false],
            ])->count();

        }
        return $usersWithWhomIgetConversation;
    }

    public function getMessagesFor(Request $request)
    {
        $userId = $request->userId;
        if(!$request->ajax()){
            if($request->skip){
                abort(404);
            }
        }
        try {
            $this->authorize('doesConversationExist', [Message::class, $userId]);
        } catch (AuthorizationException $e) {
            abort(404);
        }

        // setting all messages as read
        if (Message::where([
            ['from', $userId],
            ['to', Auth::user()->id],
            ['is_read', false]
        ])->update(['is_read' => true])) {
            // set notification that all messages have been read
            User::find($userId)->notify(new MessagesHaveBeenRead(['isRead' => true, 'from' => $userId]));
        }


        $myId = Auth::user()->id;
        $skip = (int)$request->skip ?? 0;

        $messages = Message::where(function ($q) use ($myId, $userId) {
            $q->where([
                ['from', $myId],
                ['to', $userId],
            ])->orWhere([
                ['from', $userId],
                ['to', $myId],
            ]);
        })->orderBy('created_at', 'desc')->skip($skip)->take(50)->get();
        $messages = $messages->reverse();
        if ($messages->isNotEmpty()) {

            // stacking messages of 1 user to 1 array,
            // new array
            // ( from 1, to 2, messages = [message1,message2], from 2 to 1, messages = [message1,message2],from 1, to 2, messages = [message1,message2]...)
            $newMessagesCollection = new Collection();
            $currentFrom = $messages->first()->from;
            $currentTo = $messages->first()->to;
            $currentMessages = [];
            foreach ($messages as $message) {
                if ($currentFrom == $message->from) {
                    $currentMessages[] = [
                        'is_read' => $message->is_read,
                        'text' => $message->text,
                        'created_at' => (string)$message->created_at];
                } else {
                    $newMessagesCollection->push([
                        'from' => $currentFrom,
                        'to' => $currentTo,
                        'messages' => $currentMessages
                    ]);
                    $currentFrom = $message->from;
                    $currentTo = $message->to;
                    $currentMessages = [];
                    $currentMessages[] = [
                        'is_read' => $message->is_read,
                        'text' => $message->text,
                        'created_at' => (string)$message->created_at];
                }
            }
            // for last element, because we skip it, by continue
            // ( i could check if it's not last element then continue, but i think it will be more expensive for productivity,
            // because if we would got 2millions messages checking every time will be very costly)
            // we have 1 more stack of messages, which will not pass through else section previously in loop
            // so i decided to do some actions for last stack of messages, because we need to write it
            $lastMessage = $newMessagesCollection->pop(); // firstly getting the last element
            if (isset($lastMessage) && $lastMessage['from'] == $currentFrom) {         // checking if sender id of last messages in ready collection is the same as it's in the last stack of messages
                foreach ($currentMessages as $currentMessage) {  // if so, the whole array we push to messages array of last item of collection
                    $lastMessage['messages'][] = $currentMessage;
                }
                $newMessagesCollection->push($lastMessage); // pushing the element back to the collection, because we popped it
            } else {
                if (isset($lastMessage)) {
                    // if sender id is not the same, it means that we have another new stack of messages from another user
                    $newMessagesCollection->push($lastMessage); // pushing the last element back to collection, because we popped it
                }
                // adding a new stack of messages from another user
                $newMessagesCollection->push([
                    'from' => $currentFrom,
                    'to' => $currentTo,
                    'messages' => $currentMessages
                ]);
            }
            if ($request->ajax()) {
                return response()->json(['messages' => $newMessagesCollection]);
            } else {
                return view('pages.messages.conversation', [
                    'messages' => $newMessagesCollection,
                    'userConversationWith' => User::findOrFail($userId)
                ]);
            }
        }
        abort(500,'No messages more!!!');
    }

    public function sendMessage(Request $request)
    {
        try {
            $this->authorize('sendTo', [Message::class, User::findOrFail($request->to)]);
        } catch (AuthorizationException $e) {
            abort(403, 'You can\'t send message right now!!! Try reload the page');
        }
        try {
            $newMessage = Message::create([
                'from' => Auth::user()->id,
                'to' => $request->to,
                'text' => $request->text
            ]);
            broadcast(new NewMessage($newMessage));

            $newMessage->to_user->notify(new NewMessageNotification([
                'message' => $newMessage,
                'from_user' => $newMessage->from_user,
                'newMessagesCount' => count($newMessage->to_user->newMessages())]));

            if ($request->ajax()) {
                return response()->json($newMessage);
            } else {
                return back()->with('success', 'Message was sent successfully!');
            }
        } catch (\Exception $e) {

            abort(500, $e->getMessage());
        }
    }

    public function setMessagesAsRead($from)
    {
        if (Message::where([
            ['from', $from],
            ['to', Auth::user()->id],
            ['is_read', false]
        ])->update(['is_read' => true])) {
            // set notification that all messages have been read
            User::find($from)->notify(new MessagesHaveBeenRead(['isRead' => true, 'from' => $from]));
        }
    }

    public function canMessageSend(Request $request)
    {
        $userConversationWith = User::findOrFail($request->userConversationWith['id']);
        try {
            $this->authorize('sendTo', [Message::class, $userConversationWith]);
            return response()->json(true);
        } catch (AuthorizationException $e) {
            return response()->json(false);
        }
    }
}
