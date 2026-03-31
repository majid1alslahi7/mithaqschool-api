<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RevokePermissionFromRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guardName = $this->input('guard_name', config('auth.defaults.guard', 'web'));

        return [
            'role_id' => ['required_without:role_name', 'integer', Rule::exists('roles', 'id')->where('guard_name', $guardName)],
            'role_name' => ['required_without:role_id', 'string', Rule::exists('roles', 'name')->where('guard_name', $guardName)],
            'permission_id' => ['required_without:permission_name', 'integer', Rule::exists('permissions', 'id')->where('guard_name', $guardName)],
            'permission_name' => ['required_without:permission_id', 'string', Rule::exists('permissions', 'name')->where('guard_name', $guardName)],
            'guard_name' => 'sometimes|string',
        ];
    }
}
