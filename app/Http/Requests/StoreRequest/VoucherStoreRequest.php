<?php

namespace App\Http\Requests\StoreRequest;

use Illuminate\Foundation\Http\FormRequest;

class VoucherStoreRequest extends FormRequest
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
			'check_no' => 'nullable|string',
            'voucher_no' => 'required|string',
			'type' => 'required|string|in:Cash,Disbursement',
			'stakeholder_id' => 'required|numeric|exists:stakeholder,id',
			'reference_no' => 'nullable|string|unique:payment_request,prf_no',
			'status' => 'required|string|in:draft,posted',
			'account_id' => 'required|numeric|exists:accounts,id',
			'particulars' => 'nullable|string',
			'net_amount' => 'required|numeric',
			'amount_in_words' => 'nullable|string',
			'voucher_date' => 'required|date|date_format:Y-m-d',
			'date_encoded' => 'required|date|date_format:Y-m-d',
			'book_id' => 'required|numeric|exists:books,id',
            'approvals' => 'required|array',
			'details' => 'required|min:1|array',
			'details.*.account_id' => 'required|numeric|exists:accounts,id',
			'details.*.stakeholder_id' => 'required|numeric|exists:stakeholder,id',
			'details.*.debit' => 'nullable|numeric',
			'details.*.credit' => 'nullable|numeric',
        ];
    }
}
