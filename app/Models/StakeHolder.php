<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StakeHolder extends Model
{
    use HasFactory;

	protected $table = 'stakeholder';
	public $timestamps = true;
	protected $fillable = [
        'id',
		'name',
		'type',
		'source_id'
	];

	public function forms()
    {
        return $this->hasMany(Form::class);
    }
}
