<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeworkSubmissionRequest extends FormRequest
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
            'homework_id' => 'required|exists:homework,id',
            'student_id' => 'required|exists:students,id',
            'submission_date' => 'required|date',
            'file_path' => 'nullable|string',
            'grade' => 'nullable|numeric|min:0',
        ];
    }
}
