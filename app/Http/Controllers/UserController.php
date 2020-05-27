<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function allUsers()
    {
        if($search = \Request::get('q')){
            $allUsers = User::where(function ($query) use ($search){
               $query->where('id','LIKE',"%$search%")
                   ->orWhere('name','LIKE',"%$search%")
                   ->orWhere('nickname','LIKE',"%$search%")
                   ->orWhere('surname','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $allUsers = User::paginate(20);
        }

        if($allUsers->currentPage() > $allUsers->lastPage()) abort(404);
        return view('pages.users.allUsers', [
            'allUsers' => $allUsers,
        ]);
    }
}
