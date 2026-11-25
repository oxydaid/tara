<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorPartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'part_id' => ['required', 'string', 'max:60', 'regex:/^[a-zA-Z0-9_\-]+$/'],
            'color' => ['required', 'string', 'max:30', 'regex:/^(#[0-9a-fA-F]{3,8}|white|[a-z]+)$/i'],
            'canvas_num' => 'nullable|integer|between:1,4',
        ];
    }
}
