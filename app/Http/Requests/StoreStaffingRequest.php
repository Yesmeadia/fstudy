<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'staffing' => ['required', 'array'],
            'staffing.*.position_id' => ['required', 'exists:staffing_positions,id'],
            'staffing.*.required_qty' => ['required', 'integer', 'min:0'],
            'staffing.*.existing_qty' => ['required', 'integer', 'min:0'],
            'staffing.*.monthly_salary' => ['required', 'numeric', 'min:0'],
        ];
    }
}
