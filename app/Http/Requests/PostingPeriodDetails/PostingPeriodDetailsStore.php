<?php

namespace App\Http\Requests\PostingPeriodDetails;

use Illuminate\Foundation\Http\FormRequest;

class PostingPeriodDetailsStore extends FormRequest
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
            'posting_period_id' => 'required|exists:posting_periods,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:open,close',
        ];
    }
}
