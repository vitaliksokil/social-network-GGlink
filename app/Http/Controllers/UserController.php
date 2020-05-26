<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function allUsers()
    {
        $allUsers = User::paginate(20);
        if($allUsers->currentPage() > $allUsers->lastPage()) abort(404);
        return view('pages.users.allUsers', [
            'allUsers' => $allUsers
        ]);
    }
}
