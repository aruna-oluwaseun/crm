<?php

namespace App\Events;

use App\Models\StockMovement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ManualStockAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $movement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StockMovement $movement)
    {
        $this->movement = $movement;
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
