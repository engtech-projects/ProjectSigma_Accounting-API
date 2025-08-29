<?php

namespace App\Http\Requests\PayrollRequest;

use Illuminate\Foundation\Http\FormRequest;

class PayrollRequestFilter extends FormRequest
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
            'prf_no' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'key' => 'nullable|string|max:255',
        ];
    }
}
