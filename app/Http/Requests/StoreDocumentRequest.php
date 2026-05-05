<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:document_categories,id'],
            'employee_id' => ['nullable', 'exists:employee_profiles,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'is_private' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'document.max' => 'Document file must not exceed 10MB.',
            'expires_at.after' => 'Expiry date must be in the future.',
        ];
    }
}
