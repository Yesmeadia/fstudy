<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            // Location
            'state' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'taluk_tehsil' => ['nullable', 'string', 'max:100'],
            'village_town' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string'],
            'pincode' => ['required', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'nearest_major_road' => ['nullable', 'string', 'max:255'],
            'distance_from_main_road_km' => ['nullable', 'numeric', 'min:0'],
            'accessibility_description' => ['nullable', 'string'],
            'public_transport_available' => ['boolean'],
            'transport_details' => ['nullable', 'string'],

            // Land Details
            'total_land_area' => ['required', 'numeric', 'min:0.1'],
            'area_unit' => ['required', 'string', 'max:50'],
            'ownership_type' => ['required', 'string'],
            'survey_number' => ['nullable', 'string', 'max:100'],
            'land_registration_details' => ['nullable', 'string', 'max:255'],
            'lease_start_date' => ['nullable', 'date'],
            'lease_expiry_date' => ['nullable', 'date', 'after_or_equal:lease_start_date'],
            'building_area' => ['nullable', 'numeric', 'min:0'],
            'playground_area' => ['nullable', 'numeric', 'min:0'],
            'open_area' => ['nullable', 'numeric', 'min:0'],
            'is_land_title_clear' => ['boolean'],
            'land_remarks' => ['nullable', 'string'],
        ];
    }
}
