<?php

namespace App\Http\Requests\Api\v1\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalBookRequest extends FormRequest
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
            "book_code" => 'required|string',
            "book_name" => "required|string",
            "book_src" => "nullable|string",
            "book_ref" => "nullable|string",
            "book_flag" => "nullable|string",
            "book_head" => "nullable|string",
            "account_id" => "required|integer"
        ];
    }
}
