<?php

namespace App\Http\Requests\Api\v1\Store;

use App\Enums\ToIncrease;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreAccountCategoryRequest extends FormRequest
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
            "account_category" => "required|string",
            "to_increase" => [new Enum(ToIncrease::class)],
        ];
    }
}
