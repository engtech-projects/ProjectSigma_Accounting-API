<?php

namespace App\Http\Requests\api\v1\update;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVoucherRequest extends FormRequest
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
        	'voucher_no' => ['required', 'string'],
			'particulars' => ['nullable', 'string'],
			'net_amount' => ['required', 'numeric'],
			'payee' => ['nullable', 'string'],
			'amount_in_words' => ['nullable', 'string'],
			'created_by' => ['nullable'],
			'date_encoded' => ['required','date','date_format:Y-m-d'],
			'voucher_date' => ['required','date','date_format:Y-m-d'],
			'status' => ['required', 'string'],
			'line_items' => ['required', 'min:1', 'array'],
			'line_items.*.account_id' => ['required', 'numeric'],
			'line_items.*.contact' => ['nullable'],
			'line_items.*.debit' => ['nullable', 'numeric'],
			'line_items.*.credit' => ['nullable', 'numeric'],
        ];
    }
}
