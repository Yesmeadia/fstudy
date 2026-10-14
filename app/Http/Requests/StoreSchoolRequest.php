<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'proposed_name' => ['nullable', 'string', 'max:255'],
            'school_code' => ['nullable', 'string', 'max:50'],
            'applicant_name' => ['required', 'string', 'max:255'],
            'applicant_email' => ['nullable', 'email', 'max:255'],
            'applicant_phone' => ['required', 'string', 'regex:/^(\+91[\-\s]?)?[6-9]\d{9}$/'],
            'management_organization' => ['required', 'string', 'max:255'],
            'trust_society_type' => ['required', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'registration_date' => ['nullable', 'date'],
            'address' => ['required', 'string'],
            'state' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'website' => ['nullable', 'url', 'max:255'],
            'established_year' => ['nullable', 'integer', 'min:1800', 'max:'.(date('Y') + 5)],
            'is_existing_school' => ['boolean'],
            'board' => ['required', 'string'],
            'medium' => ['required', 'string', 'max:50'],
            'school_type' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_phone.regex' => 'The contact phone number must be a valid 10-digit Indian mobile number (e.g. 9876543210).',
        ];
    }
}
