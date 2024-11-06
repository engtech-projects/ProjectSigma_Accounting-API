<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasFormable;
use App\Enums\FormStatus;

class Form extends Model
{
    use HasFactory, HasFormable;

	protected $table = 'forms';

	protected $fillable = [
		'stakeholder_id',
		'formable_id',
		'formable_type',
		'status'
	];

	public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }

    public function vouchers() : HasMany
    {
        return $this->hasMany(Voucher::class);
    }

	public function updateStatus($newStatus) : bool
	{
		if ($this->status === $newStatus->value) {
            return false;
        }

		$this->status = $newStatus->value;
        return $this->save();
	}

}