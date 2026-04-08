<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'group_id',
        'group_ids',
        'combined_groups',
        'day',
        'hour_number',
        'start_time',
        'end_time',
        'subject',
        'teacher_id',
        'is_fixed',
        'is_recess',
    ];

    protected $casts = [
        'is_fixed' => 'boolean',
        'is_recess' => 'boolean',
        'group_ids' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function groups()
    {
        if ($this->group_ids) {
            return Group::whereIn('id', $this->group_ids)->get();
        }
        return collect([$this->group]);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function isCombined(): bool
    {
        return !empty($this->group_ids) && count($this->group_ids) > 1;
    }

    public function isRecess(): bool
    {
        return $this->is_recess === true;
    }

    public function getDisplayGroupsAttribute(): string
    {
        if ($this->isCombined()) {
            $groups = $this->groups;
            return $groups->map(fn($g) => $g->name)->join(' + ');
        }
        return $this->group ? $this->group->name : 'N/A';
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
