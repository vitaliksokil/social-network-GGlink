<?php

namespace App\Policies;

use App\Room;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    public function delete(User $user,$roomID){
        $room = Room::findOrFail($roomID);
        return $user->id === $room->creator_id;
    }
}
