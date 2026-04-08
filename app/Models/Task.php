<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = ['group_ids', 'title', 'description', 'due_date', 'max_points', 'attachments'];

    protected $casts = [
        'due_date' => 'date',
        'attachments' => 'array',
        'group_ids' => 'array',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'task_group', 'task_id', 'group_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function getGroupIdsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return $value ? json_decode($value, true) : [];
    }

    public function getGroupIdAttribute()
    {
        $ids = $this->group_ids;
        return is_array($ids) && count($ids) > 0 ? $ids[0] : null;
    }
}
