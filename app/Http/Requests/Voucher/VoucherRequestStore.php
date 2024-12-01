<?php

namespace App\Http\Requests\Voucher;

use App\Http\Traits\HasApprovalValidation;
use Illuminate\Foundation\Http\FormRequest;

class VoucherRequestStore extends FormRequest
{
    use HasApprovalValidation;

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
            'stakeholder_id' => 'required|numeric|exists:stakeholder,id',
            'reference_no' => 'nullable|string|unique:payment_request,prf_no',
            'journal_entry_id' => 'required|numeric|exists:journal_entry,id',
            'particulars' => 'nullable|string',
            'net_amount' => 'required|numeric',
            'amount_in_words' => 'required|string',
            'voucher_date' => 'required|date|date_format:Y-m-d',
            'approvals' => 'required|array',
            'details' => 'required|min:1|array',
            'details.*.account_id' => 'required|numeric|exists:accounts,id',
            'details.*.stakeholder_id' => 'nullable|numeric|exists:stakeholder,id',
            'details.*.debit' => 'nullable|numeric',
            'details.*.credit' => 'nullable|numeric',
            ...$this->storeApprovals(),
        ];
    }
}
