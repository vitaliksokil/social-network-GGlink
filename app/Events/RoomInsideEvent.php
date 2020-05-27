<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RoomInsideEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $roomID;
    public $data;
    public $action;

    /**
     * Create a new event instance.
     *
     * @param $roomID
     * @param $data
     */
    public function __construct($roomID,$data,$action)
    {
        $this->roomID = $roomID;
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
        return new Channel('room.'.$this->roomID);
    }
    public function broadcastWith(){
        if($this->action === 'create'){
            return ['newMember' => $this->data];
        }elseif ($this->action === 'delete'){
            return ['deletedMember' => $this->data];
        }elseif ($this->action === 'joinTeam'){
            return ['joinTeam' => $this->data];
        }elseif ($this->action === 'lockRoom'){
            return ['lockRoom' => $this->data];
        }elseif ($this->action === 'newMessage'){
            return ['newMessage' => $this->data];
        }
    }
}
