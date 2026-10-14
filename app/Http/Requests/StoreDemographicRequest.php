<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemographicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'population_1km' => ['required', 'integer', 'min:0'],
            'population_3km' => ['required', 'integer', 'gte:population_1km'],
            'population_5km' => ['required', 'integer', 'gte:population_3km'],
            'number_of_households' => ['required', 'integer', 'min:0'],
            'children_age_3_5' => ['required', 'integer', 'min:0'],
            'children_age_6_10' => ['required', 'integer', 'min:0'],
            'children_age_11_14' => ['required', 'integer', 'min:0'],
            'children_age_15_18' => ['required', 'integer', 'min:0'],
            'population_growth_rate' => ['required', 'numeric', 'min:-5', 'max:50'],
            'residential_development_trend' => ['required', 'string', 'max:50'],
            'employment_profile' => ['required', 'string', 'max:50'],
            'economic_profile' => ['required', 'string', 'max:50'],
            'expected_capture_rate' => ['required', 'numeric', 'min:0.5', 'max:100'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
