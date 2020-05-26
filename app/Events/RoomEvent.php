<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gameShortAddress;
    public $data;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param $gameShortAddress
     * @param $data // can be a new room or id of deleted room
     * @param $action // delete or create
     */
    public function __construct($gameShortAddress,$data,$action)
    {
        $this->gameShortAddress = $gameShortAddress;
        $this->data = $data;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('rooms.of.game.'.$this->gameShortAddress);
    }
    public function broadcastWith(){
        if($this->action === 'create'){
            return ['newRoom' => $this->data];
        }elseif ($this->action === 'delete'){
            return ['deletedRoomID' => $this->data];
        }elseif ($this->action === 'lockRoom'){
            return ['lockRoom' => $this->data];
        }elseif ($this->action === 'updateMembersCount'){
            return ['updateMembersCount' => $this->data];
        }
    }
}
