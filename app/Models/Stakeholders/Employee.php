<?php

namespace App\Models\Stakeholders;

use App\Models\PaymentRequestDetails;
use App\Models\StakeHolder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'source_id',
        'name',
    ];

    public function stakeholder()
    {
        return $this->morphOne(StakeHolder::class, 'stakeholdable');
    }

    public function paymentRequestDetails()
    {
        return $this->morphMany(PaymentRequestDetails::class, 'chargeable');
    }
}
