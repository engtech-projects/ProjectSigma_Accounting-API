<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsTotalSameAsDetails implements ValidationRule
{
    protected $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (collect($this->details)->sum('amount') != $value) {
            $fail('The total amount must be equal to the sum of the amounts of the details.');
        }
    }
}
