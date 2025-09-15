<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroupAccount extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_group_account';

    protected $fillable = [
        'account_group_id',
        'account_id',
    ];

    public $timestamps = true;
}
