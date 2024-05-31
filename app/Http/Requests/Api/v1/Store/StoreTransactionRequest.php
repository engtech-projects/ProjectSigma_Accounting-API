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
            'transaction_no' => rand(10, 100),
            'reference_no' => rand(10, 100),
            'created_by' => auth()->user()->id,
            'period_id' => 1,
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
            'transaction_no' => 'required',
            'transaction_date' => 'required|date',
            'status' => [
                'required',
                new Enum(TransactionStatus::class)
            ],
            'reference_no' => 'required',
            'period_id' => 'required',
            'transaction_type_id' => 'required|integer',
            'stakeholder_id' => 'required|integer',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
            'amount' => 'required|numeric',
            'details' => 'required|array',
            'created_by' => 'nullable|integer',
        ];
    }
}
