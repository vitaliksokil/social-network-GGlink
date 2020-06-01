<?php

namespace App\Http\Controllers;


use App\Events\RoomEvent;
use App\Events\RoomInsideEvent;
use App\Game;
use App\oneHasManyModels\RoomMember;
use App\Room;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function foo\func;

class RoomController extends Controller
{
    public function index()
    {
        $games = Game::select('id', 'title', 'short_address', 'logo')->withCount('unlockedRooms')->get()->sortByDesc('unlocked_rooms_count');
        if($search = \Request::get('q')){
            $games = $games->filter(function($item) use ($search){
                if(stristr($item->title,$search)){
                    return  $item;
                }
            });
        }

        return view('pages.rooms.index', [
            'games' => $games
        ]);
    }

    public function roomsOfGame(Request $request, $gameShortAddress)
    {
        $game = Game::select('id', 'title', 'short_address')->where('short_address', $gameShortAddress)->first();
        $rooms = Room::with('creator:id,photo')->where([
            ['game_id', $game->id],
            ['is_locked', 0],
        ])->withCount('members')->latest()->get();
        $authUserRoom = Room::where('creator_id', Auth::user()->id)->first();
        return view('pages.rooms.roomsOfGame', [
            'game' => $game,
            'rooms' => $rooms,
            'authUserRoom' => $authUserRoom,
            'myRoomGameShortAddress'=>$authUserRoom->game->short_address ?? ''
        ]);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'title' => 'required',
                'count_of_members' => 'required|integer|min:2|max:10',
                'game_id' => 'required|integer',
            ]);
            try {
                $game = Game::findOrFail($request->game_id); // check if that game exists
                $room = Room::create([
                    'title' => $request->title,
                    'count_of_members' => $request->count_of_members,
                    'game_id' => $game->id,                   // id of a game
                    'creator_id' => Auth::user()->id
                ]);

                $roomData = Room::with('creator:id,photo')->withCount('members')->findOrFail($room->id);

                event(new RoomEvent($game->short_address, $roomData->toArray(), 'create')); // send event to frontend that we have just created a new room
                return response()->json($roomData);
            } catch (\Exception $exception) {
                if (isset($room)) {
                    $room->delete();
                }
                abort(500, 'Could not create a room, it seems like you have already created a room!!! Delete your room to create a new one.');
            }
        } else {
            abort(404);
        }
    }

    public function delete($game_short_address, $id)
    {
        $room = Room::findOrFail($id);
        if ($room->game->short_address == $game_short_address && $room->creator_id == Auth::user()->id) {
            if ($room->delete()) {
                event(new RoomEvent($game_short_address, $room->id, 'delete'));
                return response()->json(['message' => 'Successfully deleted!!!', 'roomID' => $room->id]);
            } else {
                abort(500, 'Something went wrong! Reload the page and try again!');
            }
        } else {
            abort(500, 'Something went wrong! Reload the page and try again!');
        }
    }

    public function show($game_short_address, $id)
    {
        $room = Room::findOrFail($id);
        // check can user join the room, if its not close

        if ($room->is_locked == 1 && $room->creator_id != Auth::user()->id) {
            return redirect()->back()->with('flash', 'The room is locked!');
        }
        if ($room->game->short_address == $game_short_address) {
            $allMembers = DB::table('rooms')
                ->join('room_members', 'rooms.id', '=', 'room_members.room_id')
                ->join('users', 'room_members.member_id', '=', 'users.id')
                ->select(['is_joined', 'member_id', 'name', 'nickname', 'surname', 'photo'])
                ->where('room_id', $room->id)
                ->get();
            $inTeamMembers = $allMembers->where('is_joined', '=', 1)->values();
            $joinedMembers = $allMembers->where('is_joined', '=', 0)->values();

            $messages = DB::table('room_messages')
                ->join('users', 'room_messages.sender_id', '=', 'users.id')
                ->select(['sender_id', 'name', 'nickname', 'surname', 'photo', 'message', 'room_messages.created_at'])
                ->where('room_id', $room->id)->get();

            return view('pages.rooms.room', [
                'room' => $room,
                'inTeamMembers' => $inTeamMembers,
                'joinedMembers' => $joinedMembers,
                'messages' => $messages
            ]);
        } else {
            abort(404);
        }
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'member_id' => 'required|integer',
            'room_id' => 'required|integer',
        ]);
        try {
            $newMember = RoomMember::create($request->all());
            $eventData = [
                'is_joined' => $newMember->is_joined,
                'member_id' => $newMember->member->id,
                'name' => $newMember->member->name,
                'nickname' => $newMember->member->nickname,
                'surname' => $newMember->member->surname,
                'photo' => $newMember->member->photo
            ];

            event(new RoomEvent($newMember->room->game->short_address, [
                'room_id'=> $newMember->room->id,
                'members_count' => count($newMember->room->members)
            ], 'updateMembersCount'));

            event(new RoomInsideEvent($request->room_id, $eventData, 'create'));
            return response()->json($newMember->member);
        } catch (\Exception $exception) {
            abort(500, 'You didn\'t join the room! Try reload the page and try again!');
        }
    }

    public function deleteMember(Request $request)
    {
        $request->validate([
            'member_id' => 'required|integer',
            'room_id' => 'required|integer',
        ]);

        $roomMember = RoomMember::where([
            ['member_id', $request->member_id],
            ['room_id', $request->room_id]
        ])->firstOrFail();
        if ($roomMember->delete()) {
            event(new RoomEvent($roomMember->room->game->short_address, [
                'room_id'=>$roomMember->room->id,
                'members_count' => count($roomMember->room->members)
            ], 'updateMembersCount'));

            event(new RoomInsideEvent($request->room_id, ['member_id' => $roomMember->member->id, 'is_joined' => $roomMember->is_joined], 'delete'));
            return response()->json($roomMember->member);
        }
    }

    public function addMemberToTeam(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
        ]);
        // check if room is not full,
        try {
            $this->authorize('joinTeam', [RoomMember::class, $request->room_id]);
        } catch (AuthorizationException $e) {
            abort(403, 'Team is full');
        }

        $roomMemberToTeam = RoomMember::where([
            ['room_id', $request->room_id],
            ['member_id', Auth::user()->id],
            ['is_joined', 0]
        ])->firstOrFail();
        $roomMemberToTeam->is_joined = 1;
        if ($roomMemberToTeam->save()) {
            $eventData = [
                'is_joined' => $roomMemberToTeam->is_joined,
                'member_id' => $roomMemberToTeam->member->id,
                'name' => $roomMemberToTeam->member->name,
                'nickname' => $roomMemberToTeam->member->nickname,
                'surname' => $roomMemberToTeam->member->surname,
                'photo' => $roomMemberToTeam->member->photo
            ];

            event(new RoomInsideEvent($request->room_id, $eventData, 'joinTeam'));
        }
    }

    public function lockUnlockTheRoom(Request $request)
    {
        $request->validate([
            'room' => 'required',
        ]);
        if (Auth::user()->id == $request->room['creator_id']) {
            $room = Room::findOrFail($request->room['id'])->withCount('members')->first();
            if ($room->is_locked == 0) {
                $room->update([
                    'is_locked' => 1
                ]);
            } else {
                $room->update([
                    'is_locked' => 0
                ]);
            }
            // send event that room was locked!!!
            $creatorPhoto = $room->creator->photo;
            $room->creator = ['photo' => $creatorPhoto];

            event(new RoomEvent($room->game->short_address, $room->toArray(), 'lockRoom'));
            event(new RoomInsideEvent($room->id, $room->is_locked, 'lockRoom'));
        } else {
            abort(403, 'You are not creator of the room!');
        }
    }

    public function kickMember($member_id, $room_id)
    {

        $room = Room::find($room_id);
        if (Auth::user()->id == $room->creator_id) {
            $roomMember = RoomMember::where([
                ['room_id', $room->id],
                ['member_id', $member_id]
            ])->first();
            if (isset($roomMember) && $roomMember->delete()) {
                event(new RoomInsideEvent($room->id, ['member_id' => $member_id, 'is_joined' => $roomMember->is_joined,'is_kicked'=>true], 'delete'));
            }
        } else {
            abort(403, 'You are not creator of the room!');
        }
    }

    public function addMessage(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer',
            'message' => 'required'
        ]);
        $messageID = DB::table('room_messages')->insertGetId([
            'room_id' => $request->room_id,
            'sender_id' => Auth::user()->id,
            'message' => $request->message,
            'created_at' => Carbon::now(),
        ]);
        $message = DB::table('room_messages')
            ->join('users', 'room_messages.sender_id', '=', 'users.id')
            ->select(['sender_id', 'name', 'nickname', 'surname', 'photo', 'message', 'room_messages.created_at'])
            ->where('room_messages.id',$messageID)->first();

        event(new RoomInsideEvent($request->room_id, $message, 'newMessage'));
    }
}
