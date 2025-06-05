<?php

namespace App\Events\General\Body\Devices;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DevicesUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // public $devices;

    // public function __construct($devices)
    // {
    //     $this->devices = $devices;
    // }

    // public function broadcastOn()
    // {
    //     return new Channel('devices');
    // }

    public $result;
    // public $broadcastQueue = null;

    public function __construct($result)
    {
        $this->result = $result;
        // \Log::info('DevicesUpdated Event Fired', ['result' => $this->result]);
    }

    public function broadcastOn()
    {
        return ['device-channel'];
    }

    public function broadcastAs()
    {
        return 'device-event';
    }

}
