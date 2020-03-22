<?php

namespace App\Http\Controllers;

use App\Traits\UploadTrait;
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

    public function profile()
    {
        return view('pages.profile.profile');
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
        if(Hash::check($request->password,Auth::user()->password)){
            $user = Auth::user();
            $user->update($request->except('password'));
            return redirect()->route('edit')->with('success','Successfully updated!!!');
        }else{
            return redirect()->back()->withErrors(['password' => 'Incorrect password']);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'nickname' => ['required', 'string', 'max:255'],
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

        if(Hash::check($request->password,Auth::user()->password)) {
            $user = Auth::user();
            $user->email = $request->email;
            $user->email_verified_at = Null;
            $user->save();
            return redirect()->route('verification.resend');
        }else{
            return redirect()->back()->withErrors(['password' => 'Incorrect password']);
        }
    }

    public function editPassword(){
        return view('pages.profile.editPassword');
    }
    public function updatePassword(Request $request){
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if(Hash::check($request->currentPassword,Auth::user()->password)) {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('editPassword')->with('success','Successfully changed!!!');
        }else{
            return redirect()->back()->withErrors(['password' => 'Incorrect password']);
        }
    }
}
