<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'requirement_id' => ['required', 'exists:document_requirements,id'],
            'document_file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,docx,xlsx', 'max:10240'],
        ];
    }
}
