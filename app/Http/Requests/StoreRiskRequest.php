<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isReviewer() || auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:risk_categories,id'],
            'risk_description' => ['required', 'string'],
            'probability' => ['required', 'integer', 'between:1,5'],
            'impact' => ['required', 'integer', 'between:1,5'],
            'mitigation_strategy' => ['required', 'string'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:open,mitigated,accepted,closed'],
        ];
    }
}
