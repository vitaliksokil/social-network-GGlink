<?php

namespace App\Http\Controllers;

use App\FriendShip;
use App\Traits\RecipientIdTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function foo\func;

class FriendShipController extends Controller
{
    use RecipientIdTrait;

    public function index()
    {
        $user = Auth::user();

        if ($search = \Request::get('q')) {
            $friends = $user
                ->friends()->filter(function ($item) use ($search) {
                    if (
                        stristr($item->id, $search)
                        ||
                        stristr($item->name, $search)
                        ||
                        stristr($item->nickname, $search)
                        ||
                        stristr($item->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $friends = $user->friends();
        }

        return view('pages.friends.friendsAll', [
            'friends' => $friends,
            'user' => $user,
        ]);
    }

    public function friendsById($id)
    {
        $user = User::find($id);
        if ($search = \Request::get('q')) {
            $friends = $user
                ->friends()->filter(function ($item) use ($search) {
                    if (
                        stristr($item->id, $search)
                        ||
                        stristr($item->name, $search)
                        ||
                        stristr($item->nickname, $search)
                        ||
                        stristr($item->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $friends = $user->friends();
        }
        return view('pages.friends.friendsAll', [
            'user' => $user,
            'friends' => $friends,
        ]);
    }

    public function friendsOnline()
    {
        $user = Auth::user();
        if ($search = \Request::get('q')) {
            $onlineFriends = $user
                ->onlineFriends()
                ->filter(function ($item) use ($search) {
                    if (
                        stristr($item->id, $search)
                        ||
                        stristr($item->name, $search)
                        ||
                        stristr($item->nickname, $search)
                        ||
                        stristr($item->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $onlineFriends = $user->onlineFriends();
        }
        return view('pages.friends.friendsOnline', [
            'onlineFriends' => $onlineFriends,
            'user' => $user,
        ]);
    }

    public function friendsNew()
    {
        $user = Auth::user();
        if ($search = \Request::get('q')) {
            $newFriends = $user
                ->new_friends
                ->filter(function ($item) use ($search) {
                    if (
                        stristr($item->sender->id, $search)
                        ||
                        stristr($item->sender->name, $search)
                        ||
                        stristr($item->sender->nickname, $search)
                        ||
                        stristr($item->sender->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $newFriends = $user->new_friends;
        }
        return view('pages.friends.friendsNew', [
            'newFriends' => $newFriends,
            'user' => $user,
        ]);
    }

    public function friendsRequestSent()
    {
        $user = Auth::user();
        if ($search = \Request::get('q')) {
            $requestedPeople = $user
                ->requested_people
                ->filter(function ($item) use ($search) {
                    if (
                        stristr($item->receiver->id, $search)
                        ||
                        stristr($item->receiver->name, $search)
                        ||
                        stristr($item->receiver->nickname, $search)
                        ||
                        stristr($item->receiver->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $requestedPeople = $user->requested_people;
        }
        return view('pages.friends.friendsRequestSent', [
            'requestedPeople' => $requestedPeople,
            'user' => $user,]);
    }

    public function friendsOnlineById($id)
    {
        $user = User::find($id);
        if ($search = \Request::get('q')) {
            $onlineFriends = $user
                ->onlineFriends()
                ->filter(function ($item) use ($search) {
                    if (
                        stristr($item->id, $search)
                        ||
                        stristr($item->name, $search)
                        ||
                        stristr($item->nickname, $search)
                        ||
                        stristr($item->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $onlineFriends = $user->onlineFriends();
        }
        return view('pages.friends.friendsOnline', [
            'user' => $user,
            'onlineFriends' => $onlineFriends,
        ]);
    }

    public function mutualFriends($id)
    {
        $user = User::find($id);
        if ($search = \Request::get('q')) {
            $mutualFriends = $user
                ->mutualFriends()
                ->filter(function ($item) use ($search) {
                    if (
                        stristr($item->id, $search)
                        ||
                        stristr($item->name, $search)
                        ||
                        stristr($item->nickname, $search)
                        ||
                        stristr($item->surname, $search)
                    ) {
                        return true;
                    } else {
                        return false;
                    }
                });
        } else {
            $mutualFriends = $user->mutualFriends();
        }


        return view('pages.friends.friendsMutual', [
            'mutualFriends' => $mutualFriends,
            'user' => $user
        ]);
    }


    public function addFriend(Request $request)
    {
        $receiver_id = $this->getRecipientId($request);
        $sender_id = Auth::user()->id;
        if ($receiver_id != $sender_id) {
            if (FriendShip::create([
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id
            ])) {
                return back()->with('success', 'Friendship request was successfully sent');
            } else {
                return back()->with('error', 'Something went wrong');
            }
        } else {
            abort(500);
        }

    }

    public function friendAccept(Request $request)
    {
        $sender_id = $request->sender_id;
        $receiver_id = $request->receiver_id;

        $friendShip = FriendShip::where([
            ['sender_id', $sender_id],
            ['receiver_id', $receiver_id],
        ])->first();
        if ($friendShip) {
            $friendShip->status = 1;
            $friendShip->save();
            return back()->with('success', 'New friend added!!!');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }

    public function deleteFriend($id)
    {
        $user_id = Auth::user()->id;
        $friendship = FriendShip::findFriendShip($user_id, $id)->first();
        if ($friendship->delete()) {
            return redirect()->back()->with('success', 'Successfully deleted from friends!!!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
