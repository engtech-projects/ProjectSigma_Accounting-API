<?php

namespace App\Http\Requests\JournalEntry;

use Illuminate\Foundation\Http\FormRequest;

class JournalEntryRequestStore extends FormRequest
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
            'journal_no' => 'required|string|unique:journal_entry,journal_no,NULL,id,deleted_at,NULL',
            'voucher_id' => 'nullable|string',
            'particulars' => 'nullable|string',
            'entry_date' => 'required|date|date_format:Y-m-d',
            'reference_no' => 'nullable|string',
            'remarks' => 'required|string',
            'payment_request_id' => 'required|numeric|exists:payment_request,id',
            'details' => 'required|array|min:1',
            'details.*.journalAccountInfo' => 'required|array',
            'details.*.stakeholderInformation' => 'nullable|array',
            'details.*.debit' => 'nullable|numeric',
            'details.*.credit' => 'nullable|numeric',
            'details.*.description' => 'nullable|string',
        ];
    }
}
