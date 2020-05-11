<?php

namespace App\Http\Controllers;

use App\FriendShip;
use App\Traits\RecipientIdTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendShipController extends Controller
{
    use RecipientIdTrait;

    public function index()
    {
        $user = Auth::user();
        $friends = $user->friends();
        return view('pages.friends.friendsAll', [
            'friends' => $friends,
            'user' => $user,
        ]);
    }
    public function friendsById($id){
        $user = User::find($id);
        return view('pages.friends.friendsAll', [
            'user'=>$user,
            'friends' => $user->friends(),
        ]);
    }

    public function friendsOnline()
    {
        $user = Auth::user();
        $onlineFriends = $user->onlineFriends();
        return view('pages.friends.friendsOnline', [
            'onlineFriends' => $onlineFriends,
            'user' => $user,
        ]);
    }

    public function friendsNew()
    {
        $user = Auth::user();
        $newFriends = $user->new_friends;
        return view('pages.friends.friendsNew', [
            'newFriends' => $newFriends,
            'user' => $user,
        ]);
    }

    public function friendsRequestSent()
    {
        $user = Auth::user();
        $requestedPeople = $user->requested_people;
        return view('pages.friends.friendsRequestSent', [
            'requestedPeople' => $requestedPeople,
            'user' => $user,]);
    }

    public function friendsOnlineById($id)
    {
        $user = User::find($id);
        $onlineFriends = $user->onlineFriends();
        return view('pages.friends.friendsOnline', [
            'user' => $user,
            'onlineFriends' => $onlineFriends,
        ]);
    }

    public function mutualFriends($id)
    {
        $user = User::find($id);
        $mutualFriends = $user->mutualFriends();
        return view('pages.friends.friendsMutual', [
            'mutualFriends' => $mutualFriends,
            'user' => $user
        ]);
    }


    public function addFriend(Request $request)
    {
        $receiver_id = $this->getRecipientId($request);
        $sender_id = Auth::user()->id;
        if($receiver_id != $sender_id){
            if (FriendShip::create([
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id
            ])) {
                return back()->with('success', 'Friendship request was successfully sent');
            } else {
                return back()->with('error', 'Something went wrong');
            }
        }else{
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
