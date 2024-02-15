<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelObserverEvent implements ShouldBroadcast
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
            "action" => $this->action,
            "activity_type" => $this->activityType,
            "model" => $this->model

        ];
    }

    public function broadcastAs()
    {
        return 'observer.events';
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
