<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access_api');
    }

    public function rules(): array
    {
        return [
            'api_key' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ];
    }
}
