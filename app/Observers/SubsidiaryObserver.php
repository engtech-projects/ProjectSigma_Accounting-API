<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Subsidiary;
use Illuminate\Bus\Queueable;
use App\Events\ModelObserverEvent;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Queue\SerializesModels;

class SubsidiaryObserver implements ShouldHandleEventsAfterCommit,ShouldDispatchAfterCommit
{
    use InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Handle the Subsidiary "created" event.
     */

    protected $activity;


    public function created(Subsidiary $subsidiary): void
    {
        //$this->saveActivityLog("created", $subsidiary);
        event(new ModelObserverEvent($subsidiary, 'created', 1));
    }

    /**
     * Handle the Subsidiary "updated" event.
     */
    public function updated(Subsidiary $subsidiary): void
    {
        event(new ModelObserverEvent($subsidiary, 'updated', 1));
    }

    /**
     * Handle the Subsidiary "deleted" event.
     */
    public function deleted(Subsidiary $subsidiary): void
    {
        //$this->saveActivityLog("deleted", $subsidiary);
        event(new ModelObserverEvent($subsidiary, 'deleted', 1));
    }

    /**
     * Handle the Subsidiary "restored" event.
     */
    public function restored(Subsidiary $subsidiary): void
    {
        //
    }

    /**
     * Handle the Subsidiary "force deleted" event.
     */
    public function forceDeleted(Subsidiary $subsidiary): void
    {
        //
    }

/*     private function saveActivityLog($action, $model): void
    {
        if (auth()->check()) {
            Activity::createActivity([
                "act_type_id" => 1,
                "action" => $action,
                "model" => $model,
                "action_by" => auth()->user()->id,
                "activity_date" => Carbon::now(),
            ]);
        }
    } */
}
