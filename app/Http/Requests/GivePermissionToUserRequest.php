<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\NormalizesUserIdentifiers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GivePermissionToUserRequest extends FormRequest
{
    use NormalizesUserIdentifiers;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->normalizeUserIdentifiers();
    }

    public function rules(): array
    {
        $guardName = $this->input('guard_name', config('auth.defaults.guard', 'web'));

        return [
            'user_ids' => 'required_without_all:user_id,user_email,username,phone|array|min:1',
            'user_ids.*' => 'integer|distinct|exists:users,id',
            'user_id' => 'required_without_all:user_email,username,phone,user_ids|integer|exists:users,id',
            'user_email' => 'required_without_all:user_id,username,phone,user_ids|email|exists:users,email',
            'username' => 'required_without_all:user_id,user_email,phone,user_ids|string|exists:users,username',
            'phone' => 'required_without_all:user_id,user_email,username,user_ids|string|exists:users,phone',
            'permission_id' => ['required_without:permission_name', 'integer', Rule::exists('permissions', 'id')->where('guard_name', $guardName)],
            'permission_name' => ['required_without:permission_id', 'string', Rule::exists('permissions', 'name')->where('guard_name', $guardName)],
            'guard_name' => 'sometimes|string',
        ];
    }
}
