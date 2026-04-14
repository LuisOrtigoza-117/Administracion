<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Collection;

class Group extends Model
{
    protected $fillable = ['name', 'grade', 'section', 'school_year', 'teacher_id'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function getTasksAttribute(): Collection
    {
        return Task::whereJsonContains('group_ids', $this->id)->get();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
