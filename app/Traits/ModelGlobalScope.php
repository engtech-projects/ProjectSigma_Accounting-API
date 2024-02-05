<?php

namespace App\Traits;

trait ModelGlobalScope
{

    public function scopeExcludeColumn($query, $columns)
    {
        $query->select(array_diff($this->columns, (array) $columns));
    }
}

