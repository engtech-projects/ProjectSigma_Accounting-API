<?php

namespace App\Http\Traits;

use App\Enums\DocumentStatus;

trait HasTransitions
{
    public function canTransitionTo($newStatus): bool
    {
        return $this->status->nextStatus()->value === $newStatus;
    }

    public function updateStatus($newStatus): bool
    {
        if ($this->canTransitionTo($newStatus)) {
            $this->status = $newStatus;
            $this->save();
            return true;
        }

        return false; // Transition not allowed
    }
}
