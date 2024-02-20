<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = "account_groups";
    protected $primaryKey = "account_group_id";

    protected $fillable = [
        "account_group_name",
        "type_id"
    ];
}
