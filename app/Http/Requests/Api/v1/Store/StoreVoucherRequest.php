<?php

namespace App\Http\Requests\api\v1\store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class StoreVoucherRequest extends FormRequest
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
			// 'created_by' => ['nullable'],
			'amount_in_words' => ['nullable', 'string'],
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

	protected function failedValidation(Validator $validator)
    {
        throw new ValidationException(
            $validator, 
            response()->json([
                'message' => 'The given data is invalid', 
                'errors' => $validator->errors()
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
