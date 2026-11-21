<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaborationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'user_name',
        'no_absen',
        'role',
        'last_active_at',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(CollaborationRoom::class, 'room_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
