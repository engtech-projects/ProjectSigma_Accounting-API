<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

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

    public function voucher()
    {
        return $this->hasMany(Voucher::class);
    }

    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }

    public function scopeIsUsedInAccountGroup($query)
    {
        return $query->whereHas('accountGroup');
    }

    public function scopeIsUsedInVoucher($query)
    {
        return $query->whereHas('vouchers', function ($subQuery) {
            return $subQuery->whereNotNull('book_id');
        });
    }
}
