<?php

namespace App\Http\Requests\UpdateRequest;

use Illuminate\Foundation\Http\FormRequest;

class JournalUpdateRequest extends FormRequest
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

			'journal_no' => ['required', 'string'],
            'voucher_id' => ['nullable', 'string'],
			'status' => ['required', 'string'],
			'particulars' => ['nullable', 'string'],		
			'journal_date' => ['required','date','date_format:Y-m-d'],
			'reference_no' => ['nullable', 'string'],
			'remarks' => ['nullable'],

			'details' => ['required', 'min:1', 'array'],
			'details.*.journal_entry_id' => ['required', 'numeric'],
			'details.*.account_id' => ['required', 'numeric'],
			'details.*.stakeholder_id' => ['required', 'numeric'],
			'details.*.debit' => ['nullable', 'numeric'],
			'details.*.credit' => ['nullable', 'numeric'],
			'details.*.description' => ['nullable'],
        ];
    }
}
