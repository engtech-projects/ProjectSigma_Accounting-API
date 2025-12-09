<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiquidationFormRequest extends FormRequest
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
            'project_code' => 'required|string|max:50|exists:stakeholder,name',
            'amount' => 'required|numeric',
            'request_date' => 'required|date',
            'description' => 'required|string',
            'attachment_url' => 'nullable|string',
            'details' => 'required|array',
            'details.*.receipt_no' => 'nullable|string',
            'details.*.amount' => 'required|numeric',
        ];
    }
}
