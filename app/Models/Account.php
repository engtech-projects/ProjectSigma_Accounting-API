<?php

namespace App\Models;

use App\Models\Pivot\BookAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "account_id";
    protected $fillable = [
        'account_name',
        'account_number',
        'account_description',
        'status',
        'parent_account',
        'bank_reconciliation',
        'statement',
        'type',
        'type_id',
    ];


    ## MODEL RELATIONS ##

    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }
    public function sub_account(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_account', 'account_id')->with('sub_account');
    }

    public function account_has_books(): BelongsToMany
    {
        return $this->belongsToMany(JournalBook::class, 'account_book')
            ->using(BookAccount::class)
            ->withPivot(['account_id', 'book_id']);

    }



    ## MODEL SCOPE BINDINGS ##

    public function scopeParentAccount($query)
    {
        return $query->whereNull('parent_account');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
