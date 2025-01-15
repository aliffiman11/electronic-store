<?php

namespace App\Events;

use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewOrderEvent implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function broadcastOn()
    {
        return new Channel('orders'); // Ensure this matches the channel being listened to
    }

    public function broadcastAs()
    {
        return 'new-order'; // Ensure this matches the event name being listened to
    }



    public function broadcastWith()
    {
        Log::info('Broadcasting NewOrderEvent: ', ['order' => $this->order]);
        return ['order' => $this->order];
    }
}
