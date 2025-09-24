<?php

namespace App\Http\Requests\PostingPeriodDetails;

use Illuminate\Foundation\Http\FormRequest;

class PostingPeriodDetailsFilter extends FormRequest
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
            'fiscal_year_id' => 'required',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ];
    }
}
