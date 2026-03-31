<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeworkSubmissionRequest extends FormRequest
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
            'homework_id' => 'sometimes|required|exists:homework,id',
            'student_id' => 'sometimes|required|exists:students,id',
            'submission_date' => 'sometimes|required|date',
            'file_path' => 'nullable|string',
            'grade' => 'nullable|numeric|min:0',
        ];
    }
}
