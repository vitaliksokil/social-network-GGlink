<?php

namespace App\Policies;

use App\Room;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomMemberPolicy
{
    use HandlesAuthorization;

   public function joinTeam(User $user, $room_id){
       $room = Room::findOrFail($room_id);
       if(count($room->members->where('is_joined',1)) < $room->count_of_members){
           return true;
       }else{
           return false;
       }
   }
}
