<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'designation' => ['nullable', 'string', 'max:100'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:1024'],
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.max' => 'The profile photo must not be greater than 1 MB (1024 KB).',
            'avatar.image' => 'The profile photo must be a valid image file.',
            'avatar.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, webp.',
        ];
    }
}
