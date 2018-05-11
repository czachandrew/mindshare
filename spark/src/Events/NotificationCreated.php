<?php

namespace Laravel\Spark\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * The notification instance.
     *
     * @var \Laravel\Spark\Notification
     */
    public $notification;

    /**
     * Create a new notification instance.
     *
     * @param  \Laravel\Spark\Notification  $notification
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('spark-notifications');
    }
}
