<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationScoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isReviewer() || auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'scores' => ['required', 'array'],
            'scores.*.subcriterion_id' => ['required', 'exists:evaluation_subcriteria,id'],
            'scores.*.score' => ['required', 'numeric', 'min:0'],
            'scores.*.remarks' => ['nullable', 'string', 'max:500'],
        ];
    }
}
