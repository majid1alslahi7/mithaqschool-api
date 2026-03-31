<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CacheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('clear_cache');
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|in:all,config,route,view,permission',
        ];
    }
}
