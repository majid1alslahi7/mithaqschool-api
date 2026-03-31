<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
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
            'title' => 'sometimes|string|max:200',
            'message' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id',
            'is_read' => 'sometimes|boolean',
            'read_at' => 'nullable|date',
            'is_deleted' => 'sometimes|boolean',
            'is_synced' => 'sometimes|boolean',
        ];
    }
}
