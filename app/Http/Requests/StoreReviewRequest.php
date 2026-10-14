<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isReviewer() || auth()->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'section' => ['required', 'string', 'in:academic,financial,infrastructure,legal,preliminary'],
            'status' => ['required', 'in:approved,needs_correction,rejected,under_review'],
            'comments' => ['required', 'string'],
            'recommendations' => ['nullable', 'string'],
        ];
    }
}
