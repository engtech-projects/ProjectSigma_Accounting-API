<?php

namespace App\Models\Stakeholders;

use App\Models\PaymentRequestDetails;
use App\Models\StakeHolder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'source_id',
        'name'
    ];
    public function stakeholder()
    {
        return $this->morphMany(StakeHolder::class, 'stakeholdable');
    }
    public function paymentRequestDetails()
    {
        return $this->morphMany(PaymentRequestDetails::class, 'chargeable');
    }
}
