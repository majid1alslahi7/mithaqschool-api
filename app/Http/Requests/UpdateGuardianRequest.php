<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuardianRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $guardianId = $this->route('guardian')->id;

        return [
            'user_id' => 'sometimes|nullable|integer|exists:users,id|unique:parents,user_id,' . $guardianId,
            'f_name' => 'sometimes|string|max:200',
            'l_name' => 'sometimes|string|max:200',
            'gender' => 'sometimes|string|in:male,female',
            'address' => 'sometimes|nullable|string',
            'avatar_path' => 'sometimes|nullable|string',
        ];
    }
}
