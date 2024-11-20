<?php

namespace App\Http\Requests;

use App\Enums\BankReconcillationType;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'account_name' => 'required|string|max:255',
            'account_type_id' => 'required|exists:account_types,id|exists:account_types,id',
            'account_number' => 'required|numeric|digits_between:4,10',
            'account_description' => 'required|string|max:255',
            'bank_reconcillation' =>  'required|string|max:255|in:'.implode(',', BankReconcillationType::values()),
            'statement' => 'nullable|string',
        ];
    }
}
