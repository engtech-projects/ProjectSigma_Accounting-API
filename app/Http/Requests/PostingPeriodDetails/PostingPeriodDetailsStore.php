<?php

namespace App\Http\Requests\PostingPeriodDetails;

use App\Enums\PostingPeriodStatusType;
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
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
        ];
    }
}
