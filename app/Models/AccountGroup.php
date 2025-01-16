<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'account_group';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    /**
     * The tags that belong to the task.
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_group_accounts', 'account_group_id', 'account_id');
    }
}
