<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelObserverEvent implements ShouldQueueAfterCommit, ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $action;
    public $activityType;

    /**
     * Create a new event instance.
     */
    public function __construct($model, $action, $activityType)
    {
        $this->model = $model;
        $this->action = $action;
        $this->activityType = $activityType;
    }
    public function broadcastWith()
    {
        return [
            "activity_type" => $this->activityType,
        ];
    }

    public function broadcastAs()
    {
        return 'model.observer.event';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('model-observer-channel'),
        ];
    }
}
