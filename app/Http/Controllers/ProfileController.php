<?php

namespace App\Http\Controllers;

use App\FriendShip;
use App\Post;
use App\Traits\UploadTrait;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use UploadTrait;

    public function __construct()
    {
        $this->middleware('verified');
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);

        $friends = $user->friends()->shuffle();
        $countFriends = count($friends);
        if ($countFriends < 5) {
            $friends = $friends->random($countFriends);
        } else {
            $friends = $friends->random(5);
        }


        $posts = $user->wall;
        $isFriend = Auth::user()->isFriend($user->id);
        $authUser = Auth::user();
        $isSentRequest = FriendShip::findFriendShip($user->id, $authUser->id)->where([['status', 0]])->first();
        return view('pages.profile.profile', [
            'user' => $user,
            'posts' => $posts,
            'isFriend' => $isFriend,
            'authUser' => $authUser,
            'isSentRequest' => $isSentRequest,
            'friends' => $friends
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('pages.profile.edit', ['user' => $user]);
    }

    public function updatePhoto(Request $request)
    {
        $user = Auth::user();
        $this->uploadPhoto($request, $user, 'img/profiles');
        return redirect()->route('edit');
    }

    public function update(Request $request)
    {
        $this->validator($request->all())->validate();
        if (Hash::check($request->password, Auth::user()->password)) {
            $user = Auth::user();
            $user->update($request->except('password'));
            return redirect()->route('edit')->with('success', 'Successfully updated!!!');
        } else {
            return redirect()->back()->withErrors(['password' => 'Incorrect password']);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255'],
            'about' => ['required', 'string',],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    public function editEmail()
    {
        return view('pages.profile.editEmail', ['email' => Auth::user()->email]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (Hash::check($request->password, Auth::user()->password)) {
            $user = Auth::user();
            $user->email = $request->email;
            $user->email_verified_at = Null;
            $user->save();
            return redirect()->route('verification.resend');
        } else {
            return redirect()->back()->withErrors(['password' => 'Incorrect password']);
        }
    }

    public function editPassword()
    {
        return view('pages.profile.editPassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if (Hash::check($request->currentPassword, Auth::user()->password)) {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('editPassword')->with('success', 'Successfully changed!!!');
        } else {
            return redirect()->back()->withErrors(['password' => 'Incorrect password']);
        }
    }

    public function settings()
    {

        return view('pages.profile.settings', [
            'user' => Auth::user()
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
           'wall_can_edit' => ['sometimes','min:0','max:2','integer'],
           'show_email' => ['sometimes','min:0','max:1','integer'],
        ]);
        $user = Auth::user();
        if ($user->update($request->all())) {
            return redirect()->back()->with('success', 'Successfully changed!!!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Something went wrong']);

        }


    }
}
