<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinancialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Capex array
            'capex' => ['nullable', 'array'],
            'capex.*.category' => ['required', 'string'],
            'capex.*.item_name' => ['required', 'string'],
            'capex.*.amount' => ['required', 'numeric', 'min:0'],
            'capex.*.remarks' => ['nullable', 'string'],

            // Opex array
            'opex' => ['nullable', 'array'],
            'opex.*.category' => ['required', 'string'],
            'opex.*.item_name' => ['required', 'string'],
            'opex.*.annual_amount' => ['required', 'numeric', 'min:0'],
            'opex.*.remarks' => ['nullable', 'string'],

            // Revenue projections
            'revenue' => ['nullable', 'array'],
            'revenue.*.fee_type' => ['required', 'string'],
            'revenue.*.fee_per_student' => ['required', 'numeric', 'min:0'],
            'revenue.*.year_1' => ['required', 'numeric', 'min:0'],
            'revenue.*.year_2' => ['nullable', 'numeric', 'min:0'],
            'revenue.*.year_3' => ['nullable', 'numeric', 'min:0'],
            'revenue.*.year_4' => ['nullable', 'numeric', 'min:0'],
            'revenue.*.year_5' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
