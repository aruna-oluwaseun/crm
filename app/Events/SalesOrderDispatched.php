<?php

namespace App\Events;

use App\Models\SalesOrderDispatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalesOrderDispatched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dispatch;

    /**
     * Create a new event instance.
     *
     * @param SalesOrderDispatch $dispatch
     * @param $items
     */
    public function __construct(SalesOrderDispatch $dispatch)
    {
        $this->dispatch = $dispatch;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
