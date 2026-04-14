<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

class Task extends Model
{
    protected $fillable = ['group_ids', 'title', 'description', 'due_date', 'max_points', 'attachments'];

    protected $casts = [
        'due_date' => 'date',
        'attachments' => 'array',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function getGroupsAttribute(): Collection
    {
        $ids = $this->group_ids ?? [];
        if (empty($ids)) {
            return new Collection();
        }
        return Group::whereIn('id', $ids)->get();
    }

    public function getGroupIdsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return $value ? json_decode($value, true) : [];
    }
}
