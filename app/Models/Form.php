<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Form extends Model
{
    use HasFactory;

	public function formable()
	{
		return $this->morphTo();
	}

	public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
}