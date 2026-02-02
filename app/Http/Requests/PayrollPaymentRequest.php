<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PayrollPaymentRequest extends FormRequest
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
            'payee' => 'required|string',
            'date' => 'required|date',
            'particulars' => 'required|string',
            'amount' => 'required|numeric',
            'details' => 'required|array',
            'details.*.code' => 'nullable|string',
            'details.*.account' => 'required|string',
            'details.*.type' => 'required|string|in:project,department,deduction,bank',
            'details.*.amount' => 'required|numeric',
            'attachment_url' => 'nullable|string',
        ];
    }
}
