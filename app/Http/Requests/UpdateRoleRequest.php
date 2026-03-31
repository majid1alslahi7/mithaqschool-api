<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
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
        $role = $this->route('role');
        $guardName = $this->input('guard_name', $role?->guard_name ?? config('auth.defaults.guard', 'web'));

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($role?->id)
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
                $this->route('role')?->guard_name ?? config('auth.defaults.guard', 'web')
            ),
        ]);
    }
}
