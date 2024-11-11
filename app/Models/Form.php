<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasFormable;
use App\Enums\FormStatus;
use App\Traits\HasTransitions;

class Form extends Model
{
    use HasFactory, HasFormable, HasTransitions;

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

	// public function canTransitionTo(FormStatus $newStatus): bool
    // {
    //     return $this->status->nextStatus() === $newStatus;
    // }

	// public function updateStatus(FormStatus $newStatus) : bool
	// {

	// 	if( $this->canTransitionTo($newStatus) )
	// 	{
	// 		$this->status = $newStatus->value;
	// 		$this->save();
	// 		return true;
	// 	}

    //     return $this->false();
	// }

}