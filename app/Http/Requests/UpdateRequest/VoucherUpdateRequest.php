<?php

namespace App\Http\Requests\UpdateRequest;

use Illuminate\Foundation\Http\FormRequest;

class VoucherUpdateRequest extends FormRequest
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
			'check_no' => ['nullable', 'string'],
            'voucher_no' => ['required', 'string'],
			'stakeholder_id' => ['required', 'numeric'],
			'status' => ['required', 'string'],
			'account_id' => ['required', 'numeric'],
			'particulars' => ['nullable', 'string'],
			'net_amount' => ['required', 'numeric'],
			'amount_in_words' => ['nullable', 'string'],
			'voucher_date' => ['required','date','date_format:Y-m-d'],
			'date_encoded' => ['required','date','date_format:Y-m-d'],	
			'book_id' => ['required', 'numeric'],

			'details' => ['required', 'min:1', 'array'],
			'details.*.account_id' => ['required', 'numeric'],
			'details.*.stakeholder_id' => ['required', 'numeric'],
			'details.*.debit' => ['nullable', 'numeric'],
			'details.*.credit' => ['nullable', 'numeric'],
        ];
    }
}
