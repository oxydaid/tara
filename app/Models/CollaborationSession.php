<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class CollaborationSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_label',
        'schedule_label',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(CollaborationRoom::class, 'collaboration_session_id')->orderBy('room_number');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(CollaborationSubmission::class, 'collaboration_session_id');
    }

    /**
     * Get the currently active session, creating and provisioning default rooms if needed.
     */
    public static function getActive(): self
    {
        $session = self::where('is_active', true)->orderByDesc('id')->first();

        if (! $session) {
            $session = self::orderBy('id')->first();
            if ($session) {
                $session->update(['is_active' => true]);
            } else {
                $session = self::create([
                    'name' => 'Sesi Kolaborasi 1',
                    'is_active' => true,
                ]);
            }
        }

        $session->ensureRooms();

        return $session;
    }

    /**
     * Ensure all 8 standard rooms (5 members max each) exist for this session.
     */
    public function ensureRooms(): void
    {
        for ($num = 1; $num <= 8; $num++) {
            CollaborationRoom::firstOrCreate(
                [
                    'collaboration_session_id' => $this->id,
                    'room_number' => $num,
                ],
                [
                    'name' => "Ruang Kolaborasi {$num}",
                    'theme' => 'Motif Damar Kurung',
                    'max_members' => 5,
                    'status' => 'active',
                ]
            );
        }

        CollaborationRoom::where('collaboration_session_id', $this->id)
            ->where('room_number', '<=', 8)
            ->where('max_members', '!=', 5)
            ->update(['max_members' => 5]);
    }

    /**
     * Atomically activate this session, deactivate others, and clear active members from inactive sessions.
     */
    public static function activateSession(self $session): void
    {
        DB::transaction(function () use ($session) {
            self::where('id', '!=', $session->id)->update(['is_active' => false]);
            $session->update(['is_active' => true]);
            $session->ensureRooms();

            // Clear live members on inactive sessions so students from previous schedules do not remain bound
            $inactiveRoomIds = CollaborationRoom::where('collaboration_session_id', '!=', $session->id)->pluck('id');
            if ($inactiveRoomIds->isNotEmpty()) {
                CollaborationMember::whereIn('room_id', $inactiveRoomIds)->delete();
            }
        });
    }

    /**
     * Format display label including optional class and schedule metadata.
     */
    public function displayLabel(): string
    {
        $parts = array_filter([$this->name, $this->class_label, $this->schedule_label]);

        return implode(' • ', $parts);
    }
}
