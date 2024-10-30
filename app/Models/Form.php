<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasFormable;

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

	public function approved() {
		 $this->status = 'approved';
		 return $this->save();
	}
	public function rejected() {
		$this->status = 'rejected';
		return $this->save();
	}
	public function void() {
		$this->status = 'void';
		return $this->save();
	}
	public function issued() {
		$this->status = 'issued';
		return $this->save();
	}

}