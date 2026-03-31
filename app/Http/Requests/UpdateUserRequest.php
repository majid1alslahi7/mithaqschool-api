<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or add your authorization logic here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'username' => 'sometimes|nullable|string|max:100|unique:users,username,' . $userId,
            'phone' => 'sometimes|required|string|max:255|unique:users,phone,' . $userId,
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . $userId,
            'is_active' => 'sometimes|boolean',
            'is_deleted' => 'sometimes|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }
} 