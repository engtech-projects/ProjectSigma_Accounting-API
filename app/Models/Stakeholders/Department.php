<?php

namespace App\Models\Stakeholders;

use App\Models\PaymentRequestDetails;
use App\Models\StakeHolder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'source_id',
        'name',
        'code',
    ];

    public function stakeholder()
    {
        return $this->morphMany(StakeHolder::class, 'stakeholdable');
    }

    public function paymentRequestDetails()
    {
        return $this->morphMany(PaymentRequestDetails::class, 'chargeable');
    }

    public static function getByCode($name)
    {
        if (! $name) {
            return 'ACS';
        } else {
            return self::where('name', trim($name))->first()->code;
        }
    }
}
