<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionFlowModel extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $table = 'transaction_flow_model';

    protected $fillable = [
        'name',
        'unique_name',
        'description',
        'category',
        'user_id',
        'user_name',
        'status',
        'priority',
        'is_assignable',
    ];
}
