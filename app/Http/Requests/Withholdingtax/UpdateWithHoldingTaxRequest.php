<?php

namespace App\Http\Requests\Withholdingtax;

use App\Enums\VatType;
use App\Enums\WtaxType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWithHoldingTaxRequest extends FormRequest
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
            'id' => 'required|numeric|exists:withholding_taxes,id',
            'account_id' => 'required|exists:accounts,id',
            'wtax_name' => 'required|string|max:255|in:'.implode(',', WtaxType::values()),
            'vat_type' => 'required|string|max:255|in:'.implode(',', VatType::values()),
            'wtax_percentage' => 'required|numeric',
        ];
    }
}
