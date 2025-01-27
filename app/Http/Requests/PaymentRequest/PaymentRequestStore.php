<?php

namespace App\Http\Requests\PaymentRequest;

use App\Http\Traits\HasApprovalValidation;
use App\Rules\IsTotalSameAsDetails;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequestStore extends FormRequest
{
    use HasApprovalValidation;

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
            'description' => 'nullable|string',
            'request_date' => 'required|date|date_format:Y-m-d',
            'stakeholderInformation' => 'required|min:1|array',
            'total' => [
                'required',
                'numeric',
                new IsTotalSameAsDetails($this->all()['details']),
            ],
            'total_vat_amount' => 'required|numeric',
            'details' => 'required|min:1|array',
            'details.*.cost' => 'required|numeric',
            'details.*.vat' => 'required|numeric',
            'details.*.amount' => 'required|numeric',
            'details.*.total_vat_amount' => 'required|numeric',
            'details.*.particularGroup' => 'nullable|array',
            'details.*.stakeholderInformation' => 'required|min:1|array',
            'details.*.particulars' => 'required|string',
            ...$this->storeApprovals(),
        ];
    }
}
