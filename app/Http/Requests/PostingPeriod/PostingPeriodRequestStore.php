<?php

namespace App\Http\Requests\PostingPeriod;

use App\Enums\PostingPeriodType;
use Illuminate\Foundation\Http\FormRequest;

class PostingPeriodRequestStore extends FormRequest
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
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'status' => 'required|in:'.implode(',', PostingPeriodType::values()),
        ];
    }
}
