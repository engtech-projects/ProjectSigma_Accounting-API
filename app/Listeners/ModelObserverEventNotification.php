<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Activity;
use App\Events\ModelObserverEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelObserverEventNotification
{
    /**
     * Create the event listener.
     */
    protected $activity;
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Handle the event.
     */
    public function handle(ModelObserverEvent $event): void
    {
        $this->activity->createActivity([
            "act_type_id" => $event->activityType,
            "model" => $event->model,
            "action_by" => auth()->user()->id,
            "activity_date" => Carbon::now(),
            "action" => $event->action,
        ]);
    }
}
