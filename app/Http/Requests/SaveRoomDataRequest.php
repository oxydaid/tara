<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveRoomDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'group_name' => 'nullable|string|max:80',
            'leader_name' => 'nullable|string|max:80',
            'coloring_data' => 'nullable|array|max:600',
            'coloring_data.*' => 'nullable|string|max:30',
            'tasks_data' => 'nullable|array|max:20',
            'tasks_data.*.role' => 'nullable|string|max:60',
            'identification_data' => 'nullable|array|max:200',
            'evaluation_data' => 'nullable|array|max:200',
            'my_role' => 'nullable|string|max:60',
            'target_user_id' => 'nullable|integer',
            'target_role' => 'nullable|string|max:60',
        ];
    }
}
