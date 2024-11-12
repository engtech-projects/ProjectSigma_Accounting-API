<?php

namespace App\Http\Requests\StoreRequest;

use App\Enums\AssignTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PaymentRequestFormRequest extends FormRequest
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
            'stakeholder_id' => 'required|numeric',
			'description' => 'nullable|string',
			'request_date' => 'required|date|date_format:Y-m-d',
			'total' => 'required|numeric',
			'details' => 'required|min:1|array',
			'details.*.cost' => 'nullable|numeric',
			'details.*.vat' => 'nullable|numeric',
            'details.*.charging_type' => [
                "required",
                "string",
                new Enum(AssignTypes::class)
            ],
            'details.*.project_id' => [
                'required_if:details.*.charging_type,==,' . AssignTypes::PROJECT->value,
                'nullable',
                "integer",
                "exists:projects,id",
            ],
            'details.*.department_id' => [
                'required_if:details.*.charging_type,==,' . AssignTypes::DEPARTMENT->value,
                'nullable',
                "integer",
                "exists:departments,id",
            ],
			'details.*.amount' => 'nullable|numeric',
			'details.*.particulars' => 'nullable',
        ];
    }
}
