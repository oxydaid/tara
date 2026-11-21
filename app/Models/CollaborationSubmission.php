<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaborationSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'group_name',
        'theme',
        'leader_name',
        'tasks_data',
        'coloring_data',
        'identification_data',
        'evaluation_data',
        'submitted_at',
    ];

    protected $casts = [
        'tasks_data' => 'array',
        'coloring_data' => 'array',
        'identification_data' => 'array',
        'evaluation_data' => 'array',
        'submitted_at' => 'datetime',
    ];
}
