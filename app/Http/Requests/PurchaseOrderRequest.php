<?php

namespace App\Http\Requests;

use App\Enums\AvailabilityType;
use App\Enums\DeliveryTermTypes;
use App\Enums\PaymentTermTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'supplier' => [
                'required',
                Rule::exists('stakeholder', 'name'),
            ],
            'prf_no' => 'required',
            'request_date' => 'nullable',
            'description' => 'nullable',
            'total' => 'nullable',
            'approvals' => 'nullable',
            'request_status' => 'nullable',
            'total_vat_amount' => 'nullable',
            'delivery_terms' => ['nullable', Rule::enum(DeliveryTermTypes::class)],
            'payment_terms' => ['nullable', Rule::enum(PaymentTermTypes::class)],
            'availability' => ['nullable', Rule::enum(AvailabilityType::class)],
            'source_id' => 'required|numeric',
            'details' => 'required|array',
            'details.*.particulars' => 'nullable|string',
            'details.*.cost' => 'nullable|numeric',
            'details.*.vat' => 'nullable|numeric',
            'details.*.amount' => 'nullable|numeric',
            'details.*.total_vat_amount' => 'nullable|numeric',
            'details.*.stakeholder_id' => 'nullable|numeric',
        ];
    }
}
