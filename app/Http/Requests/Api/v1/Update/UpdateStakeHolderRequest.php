<?php

namespace App\Http\Requests\Api\v1\Update;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStakeHolderRequest extends FormRequest
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
            'title' => 'string',
            'firstname' => 'required|string',
            'middlename' => 'required|string',
            'lastname' => 'required|string',
            'suffix' => 'required|string',
            'email' => "required|email|unique:stakeholders,email,{$this->stakeholder->stakeholder_id},stakeholder_id",
            'display_name' => 'required|string',
            'street' => 'string',
            'city' => 'string',
            'state' => 'string',
            'country' => 'string',
            'phone_number' => 'string',
            'mobile_number' => 'string',
            'stakeholder_type_id' => 'required',

        ];
    }
}
