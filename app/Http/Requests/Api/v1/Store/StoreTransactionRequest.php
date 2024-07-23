<?php

namespace App\Http\Requests\Api\v1\Store;

use App\Enums\TransactionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function prepareForValidation()
    {
        $this->merge([
            'details' => json_decode($this->details, true),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_date' => 'required|date',
            'transaction_type_id' => 'required|integer',
            'stakeholder_id' => 'required|integer',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'amount' => 'required|numeric',
            'details' => 'required|array'
        ];
    }
}
