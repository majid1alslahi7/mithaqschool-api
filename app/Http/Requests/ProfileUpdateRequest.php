<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => ['nullable', 'string', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'username' => ['nullable', 'string', 'max:100', Rule::unique(User::class)->ignore($this->user()->id)],
            'user_id' => ['nullable', 'string', 'max:100', Rule::unique(User::class)->ignore($this->user()->id)],
            'enrollment_number' => ['nullable', 'integer', Rule::unique(User::class)->ignore($this->user()->id)],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female'],
            'location' => ['nullable', 'string', 'max:255'],
            'avatar_path' => ['nullable', 'string', 'max:255'],
        ];
    }
}
