<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedTeacherSchedule extends Model
{
    protected $fillable = [
        'teacher_id',
        'day',
        'hour_number',
        'start_time',
        'end_time',
        'subject',
        'grade_group',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public static function checkConflict($teacherId, $day, $hourNumber, $excludeId = null)
    {
        $query = self::where('teacher_id', $teacherId)
            ->where('day', $day)
            ->where('hour_number', $hourNumber);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
