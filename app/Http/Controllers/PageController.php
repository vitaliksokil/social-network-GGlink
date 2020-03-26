<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index(){
        return redirect()->route('profile',['id'=>Auth::user()->id]);
    }

    public function friends(){
        $user = Auth::user();
        $friends = $user->friends();
        return view('pages.friends.friendsAll', [
            'friends' => $friends,
            'user'=>$user,
        ]);
    }
    public function friendsById($id){
        $user = User::find($id);
        return view('pages.friends.friendsAll', [
            'user'=>$user,
            'friends' => $user->friends(),
        ]);
    }
}
