<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'email' => 'nullable|email:rfc,dns|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\+\-\(\)]+$/',
            'age' => 'nullable|integer|min:1|max:120',
            'city' => 'nullable|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'passport' => 'nullable|in:yes,no',
            'inquiry_date' => 'nullable|date|before_or_equal:today',
            'study_level' => 'nullable|in:foundation,diploma,bachelor,master,phd',
            'priority' => 'nullable|in:very_high,high,medium,low,very_low',
            'preferred_universities' => 'nullable|string|max:1000',
            'special_notes' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:max_width=4096,max_height=4096',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.regex' => 'First name can only contain letters, spaces, hyphens, and apostrophes.',
            'last_name.regex' => 'Last name can only contain letters, spaces, hyphens, and apostrophes.',
            'city.regex' => 'City name can only contain letters, spaces, hyphens, and apostrophes.',
            'phone.regex' => 'Phone number can only contain digits, spaces, and the following characters: + - ( )',
            'email.email' => 'Please provide a valid email address.',
            'inquiry_date.before_or_equal' => 'Inquiry date cannot be in the future.',
            'avatar.dimensions' => 'Avatar image dimensions are too large (maximum 4096x4096 pixels).',
        ];
    }

    /**
     * Sanitize input data before validation
     */
    protected function prepareForValidation(): void
    {
        // Sanitize string inputs to prevent XSS
        $this->merge([
            'first_name' => $this->sanitizeString($this->first_name),
            'last_name' => $this->sanitizeString($this->last_name),
            'email' => $this->sanitizeEmail($this->email),
            'phone' => $this->sanitizeString($this->phone),
            'city' => $this->sanitizeString($this->city),
            'preferred_universities' => $this->sanitizeString($this->preferred_universities),
            'special_notes' => $this->sanitizeString($this->special_notes),
        ]);
    }

    /**
     * Sanitize string input
     */
    private function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        return trim(strip_tags($value));
    }

    /**
     * Sanitize email input
     */
    private function sanitizeEmail(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        return filter_var(trim($value), FILTER_SANITIZE_EMAIL) ?: null;
    }
}
