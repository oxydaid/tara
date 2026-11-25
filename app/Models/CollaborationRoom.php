<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaborationRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaboration_session_id',
        'room_number',
        'name',
        'theme',
        'max_members',
        'status',
        'group_name',
        'leader_name',
        'coloring_data',
        'tasks_data',
        'identification_data',
        'evaluation_data',
    ];

    protected $casts = [
        'coloring_data' => 'array',
        'tasks_data' => 'array',
        'identification_data' => 'array',
        'evaluation_data' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(CollaborationSession::class, 'collaboration_session_id');
    }

    public function members()
    {
        return $this->hasMany(CollaborationMember::class, 'room_id')->orderBy('id', 'asc');
    }
}
