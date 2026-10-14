<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfrastructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'assessments' => ['required', 'array'],
            'assessments.*.item_id' => ['required', 'exists:infrastructure_items,id'],
            'assessments.*.status' => ['required', 'in:available,not_available,planned,not_applicable'],
            'assessments.*.quantity' => ['nullable', 'integer', 'min:0'],
            'assessments.*.score' => ['nullable', 'numeric', 'between:0,10'],
            'assessments.*.remarks' => ['nullable', 'string', 'max:500'],
        ];
    }
}
