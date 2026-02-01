<?php

namespace App\Application\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class createStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can customize this later to only allow authenticated users
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Basic store info
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],

            // Optional fields
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],
            'banner' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:4096'],

            // Verification docs
            'verification_docs' => ['nullable', 'array'],
            'verification_docs.commercial_register' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'verification_docs.tax_card' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'verification_docs.id_card' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    /**
     * Custom attribute names for cleaner error messages
     */
    public function attributes(): array
    {
        return [
            'name' => 'Store Name',
            'verification_docs.commercial_register' => 'Commercial Register Document',
            'verification_docs.tax_card' => 'Tax Card Document',
            'verification_docs.id_card' => 'ID Card Document',
        ];
    }
}
