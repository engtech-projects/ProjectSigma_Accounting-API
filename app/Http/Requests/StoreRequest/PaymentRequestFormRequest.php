<?php

namespace App\Http\Requests\StoreRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Traits\HasApprovalValidation;
class PaymentRequestFormRequest extends FormRequest
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
            'stakeholder_id' => 'required|numeric',
			'description' => 'nullable|string',
			'request_date' => 'required|date|date_format:Y-m-d',
			'total' => 'required|numeric',
			'details' => 'required|min:1|array',
			'details.*.cost' => 'nullable|numeric',
			'details.*.vat' => 'nullable|numeric',
            'details.*.id' => [
                'required',
                "integer",
            ],
			'details.*.amount' => 'nullable|numeric',
			'details.*.particulars' => 'nullable',
            ...$this->storeApprovals(),
        ];
    }
}
