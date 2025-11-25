<?php

namespace App\Http\Requests\AccountType;

use App\Enums\AccountCategory;
use App\Enums\BalanceType;
use App\Enums\NotationType;
use Illuminate\Foundation\Http\FormRequest;

class AccountTypeRequestUpdate extends FormRequest
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
            'id' => 'required|numeric',
            'account_type' => 'required|string|max:255',
            'account_category' => 'required|string|max:255|in:'.implode(',', AccountCategory::values()),
            'balance_type' => 'required|string|max:255|in:'.implode(',', BalanceType::values()),
            'notation' => 'required|string|in:'.implode(',', NotationType::values()),
        ];
    }
}
