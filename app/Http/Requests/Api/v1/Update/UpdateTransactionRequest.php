<?php

namespace App\Http\Requests\Api\v1\Update;

use App\Enums\TransactionStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'details' => json_decode($this->details, true)
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
            'transaction_no' => 'required|string',
            'transaction_date' => 'required|date',
            'status' => [
                'required',
                new Enum(TransactionStatus::class)
            ],
            'reference_no' => 'required|string',
            'transaction_type_id' => 'required|integer',
            'period_id' => 'required|integer',
            'stakeholder_id' => 'required|integer',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'amount' => 'required|numeric',
            'details' => 'required|array',
        ];
    }
}
