<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagementApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->isManagement() || auth()->user()?->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'in:approved,conditionally_approved,returned_for_correction,rejected'],
            'remarks' => ['required_if:decision,conditionally_approved,returned_for_correction,rejected', 'nullable', 'string', 'min:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'remarks.required_if' => 'Remarks are strictly mandatory when rejecting, returning, or conditionally approving an application.',
        ];
    }
}
