<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

	protected $table = 'book';

	protected $fillable = [
		'name',
		'code',
		'account_group_id',
	];

	public $timestamps = false;

	public function accountGroup(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
