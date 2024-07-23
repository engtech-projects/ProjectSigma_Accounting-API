<?php

namespace App\Models;

use App\Models\Pivot\AccountGroupBook;
use App\Models\Pivot\AccountHasGroup;
use App\Traits\HasBook;
use App\Traits\HasGroup;
use App\Traits\HasAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "account_groups";
    protected $primaryKey = "account_group_id";

    protected $fillable = [
        "account_group_name",
        "type_id"
    ];


    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_has_group', 'account_group_id', 'account_id')
            ->using(AccountHasGroup::class)
            ->withPivot(['account_id', 'account_group_id'])
            ->withTimestamps();
    }
    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'account_group_books', 'account_group_id', 'book_id')
            ->using(AccountGroupBook::class)
            ->withPivot(['account_group_id', 'book_id'])
            ->withTimestamps();
    }
}
