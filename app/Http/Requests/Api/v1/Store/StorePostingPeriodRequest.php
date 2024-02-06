<?php

namespace App\Http\Requests\Api\v1\Store;

use App\Enums\PostingPeriodStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePostingPeriodRequest extends FormRequest
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
            "period_start" => "required|date|date_format:Y-m-d",
            "period_end" => "required|date|date_format:Y-m-d",
            "status" => [new Enum(PostingPeriodStatus::class)]
        ];
    }
}
