<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chatRoom;

    /**
     * Create a new event instance.
     */
    public function __construct($message, $chatRoom)
    {
        $this->message = $message;
        $this->chatRoom = $chatRoom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chat'),
        ];
    }

    public function broadcastAs(){
        return 'chatMessage';
    }

    public function broadcastWith(){
        return [
            'message' => $this->message->only('id', 'text', 'user_id' , 'chat_room_id', 'created_at'),
        ];
    }
}
