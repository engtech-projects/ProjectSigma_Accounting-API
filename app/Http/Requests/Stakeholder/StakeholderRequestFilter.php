<?php

namespace App\Http\Requests\Stakeholder;

use App\Enums\StakeHolderType;
use Illuminate\Foundation\Http\FormRequest;

class StakeholderRequestFilter extends FormRequest
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
            'key' => 'nullable|string',
            'name' => 'nullable|string',
            'type' => 'nullable|in:'.implode(',', StakeHolderType::values()),
        ];
    }
}
