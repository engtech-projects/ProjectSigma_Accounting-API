<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePayrollRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'remarks' => 'required|string',
            'requested_by' => 'required|numeric',
            'payroll_summary_id' => 'required|numeric',
            'details' => 'required|array|min:1',
            'details.*.particular' => 'required|string',
            'details.*.amount' => 'required|numeric',
            'details.*.stakeholder' => 'nullable|string',
            'details.*.stakeholder_type' => 'nullable|string',
        ];
    }
}
