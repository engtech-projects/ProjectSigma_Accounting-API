<?php

namespace App\Http\Requests\Withholdingtax;

use App\Enums\VatType;
use App\Enums\WtaxType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWithHoldingTaxRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'wtax_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('withholding_tax', 'wtax_name')->where(function ($query) {
                    return $query->where('vat_type', $this->vat_type);
                }),
            ],
            'vat_type' => 'required|string|max:255|in:'.implode(',', VatType::values()),
            'wtax_percentage' => 'required|numeric',
        ];
    }
}
