<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentProjectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'projections' => ['required', 'array'],
            'projections.*.class_name' => ['required', 'string', 'max:50'],
            'projections.*.current_potential' => ['required', 'integer', 'min:0'],
            'projections.*.year_1_projection' => ['required', 'integer', 'min:0'],
            'projections.*.year_2_projection' => ['required', 'integer', 'min:0'],
            'projections.*.year_3_projection' => ['required', 'integer', 'min:0'],
            'projections.*.year_4_projection' => ['required', 'integer', 'min:0'],
            'projections.*.year_5_projection' => ['required', 'integer', 'min:0'],
        ];
    }
}
