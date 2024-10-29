<?php

namespace App\Traits;


trait HasFormable
{
    /**
     * Define a polymorphic relationship.
     */
    public function formable()
    {
        return $this->morphTo();
    }

	
}