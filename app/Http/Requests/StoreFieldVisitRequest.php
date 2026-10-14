<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFieldVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isReviewer() || auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'visit_date' => ['required', 'date'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'representative_name' => ['required', 'string', 'max:255'],
            'representative_contact' => ['required', 'string', 'max:50'],
            'general_remarks' => ['required', 'string'],
            'overall_recommendation' => ['required', 'in:recommended,conditionally_recommended,not_recommended'],
            'items' => ['nullable', 'array'],
            'items.*.is_verified' => ['boolean'],
            'items.*.remarks' => ['nullable', 'string'],
        ];
    }
}
