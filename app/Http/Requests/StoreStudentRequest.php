<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_active' => $this->toBoolean($this->is_active),
        ]);
    }

    /**
     * Convert to boolean
     *
     * @param $booleable
     * @return boolean
     */
    private function toBoolean($booleable)
    {
        return filter_var($booleable, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer|unique:students,user_id|exists:users,id',
            'f_name' => 'required|string|max:100',
            'l_name' => 'required|string|max:100',
            'gender' => ['required', Rule::in(['ذكر', 'أنثى'])],
            'birth_date' => 'nullable|date_format:Y-m-d',
            'address' => 'nullable|string',
            'avatar_path' => 'nullable|string',
            'parent_id' => 'nullable|exists:parents,id',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'grade_id' => 'nullable|exists:grades,id',
            'attendance_status' => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
