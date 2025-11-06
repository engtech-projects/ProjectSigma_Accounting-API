<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
            'stakeholder_id' => 'nullable',
            'prf_no' => 'required',
            'request_date' => 'nullable',
            'description' => 'nullable',
            'total' => 'nullable',
            'approvals' => 'nullable',
            'request_status' => 'nullable',
            'total_vat_amount' => 'nullable',
            'delivery_terms' => 'nullable',
            'payment_terms' => 'nullable',
            'availability' => 'nullable',
            'details' => 'required|array',
            'details.*.particulars' => 'nullable|string',
            'details.*.cost' => 'nullable|numeric',
            'details.*.vat' => 'nullable|numeric',
            'details.*.amount' => 'nullable|numeric',
            'details.*.total_vat_amount' => 'nullable|numeric',
            'details.*.stakeholder_id' => 'nullable|numeric',
            'details.*.particular_group_id' => 'nullable|numeric',
        ];
    }
}
