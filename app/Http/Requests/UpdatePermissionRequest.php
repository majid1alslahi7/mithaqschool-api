<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // سيتم التعامل مع الصلاحيات لاحقاً في الـ Policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $permissionId = $this->route('permission')->id;
        $guardName = $this->input(
            'guard_name',
            $this->route('permission')?->guard_name ?? config('auth.defaults.guard', 'web')
        );

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->ignore($permissionId)
                    ->where('guard_name', $guardName),
            ],
            'label' => 'required|string|max:255',
            'guard_name' => 'required|string|max:255',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'guard_name' => $this->input(
                'guard_name',
                $this->route('permission')?->guard_name ?? config('auth.defaults.guard', 'web')
            ),
        ]);
    }
}
