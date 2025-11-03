<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashReceivedRequest extends FormRequest
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
            'voucher_id' => 'required|numeric|exists:voucher,id',
            'received_by' => 'required|string',
            'received_date' => 'required|date|date_format:Y-m-d',
            'receipt_no' => 'required|string',
            'attachment_file_names' => 'nullable|array',
        ];
    }
}
