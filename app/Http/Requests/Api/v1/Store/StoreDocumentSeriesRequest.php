<?php

namespace App\Http\Requests\Api\v1\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentSeriesRequest extends FormRequest
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
            'series_scheme' => 'required|string',
            'series_description' => 'required|string',
            'next_number' => 'required|integer',
            'transaction_type_id' => 'required|integer'
        ];
    }
}
