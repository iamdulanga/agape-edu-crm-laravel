<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-\']+$/',
            'username' => 'required|string|max:255|unique:users|regex:/^[a-zA-Z0-9_\-]+$/',
            'email' => 'required|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|in:owner,manager,counselor',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and apostrophes.',
            'username.regex' => 'Username can only contain letters, numbers, underscores, and hyphens.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];
    }

    /**
     * Sanitize input data before validation
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => $this->sanitizeString($this->name),
            'username' => $this->sanitizeUsername($this->username),
            'email' => $this->sanitizeEmail($this->email),
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
     * Sanitize username input
     */
    private function sanitizeUsername(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        
        return strtolower(trim(strip_tags($value)));
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
