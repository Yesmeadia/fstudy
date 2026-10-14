<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNearbySchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'school_name' => ['required', 'string', 'max:255'],
            'distance_km' => ['required', 'numeric', 'min:0.1', 'max:50'],
            'board' => ['required', 'string', 'max:50'],
            'medium' => ['required', 'string', 'max:50'],
            'classes_offered' => ['required', 'string', 'max:100'],
            'student_strength' => ['required', 'integer', 'min:0'],
            'fee_structure_annual' => ['required', 'numeric', 'min:0'],
            'infrastructure_quality' => ['required', 'string', 'max:50'],
            'transportation_offered' => ['boolean'],
            'competition_level' => ['required', 'string', 'max:50'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
