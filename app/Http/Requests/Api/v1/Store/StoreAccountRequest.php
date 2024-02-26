<?php

namespace App\Http\Requests\Api\v1\Store;

use App\Enums\AccountStatus;
use App\Enums\BankReconciliation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreAccountRequest extends FormRequest
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
            'account_number' => 'required|string',
            'account_name' => 'required|string',
            'account_description' => 'required|string',
            'parent_account' => 'integer|nullable',
            'status' => [new Enum(AccountStatus::class)],
            'bank_reconciliation' => [new Enum(BankReconciliation::class)],
            'type_id' => 'required|integer',
            'opening_balance' => 'required|numeric',
            'account_group_id' => 'required|integer'
        ];
    }
}
