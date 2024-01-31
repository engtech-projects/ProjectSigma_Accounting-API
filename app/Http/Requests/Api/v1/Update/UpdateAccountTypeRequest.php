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
            "account_type_number" => 'required|string',
            "account_type" => 'required|string',
            "has_opening_balance" => 'boolean',
            "account_category_id" => 'required|integer'
        ];
    }
}
