<?php

namespace App\Http\Requests\Api\v1\Update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'account_ids' => json_decode($this->account_ids, true)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "account_group_name" => "required|string",
            "account_ids" => "array"
        ];
    }
}
