<?php

namespace App\Traits;

trait ModelGlobalScope
{

    public function scopeColumns($query, $columns= [])
    {
        $query->select($columns);
    }
}

