<?php

namespace App\Http\Requests\Api\v1\Update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountTypeRequest extends FormRequest
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
            "type_number" => 'required|string',
            "type_name" => 'required|string',
            "has_opening_balance" => 'boolean',
            "category_id" => 'required|integer'
        ];
    }
}
