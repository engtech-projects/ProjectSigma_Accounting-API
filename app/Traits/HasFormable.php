<?php

namespace App\Traits;

use App\Models\Form;

trait HasFormable
{
    /**
     * Define a polymorphic relationship.
     */
    public function formable()
    {
        return $this->morphTo();
    }

	public function forms()
    {
        return $this->morphMany(Form::class, 'formable');
    }
}
