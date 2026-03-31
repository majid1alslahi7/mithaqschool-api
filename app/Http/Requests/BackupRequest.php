<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BackupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('perform_backup');
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|in:full,database,files',
            'tables' => 'nullable|array',
            'tables.*' => 'string',
        ];
    }
}
