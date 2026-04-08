<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComputerReport extends Model
{
    protected $fillable = ['computer_id', 'description', 'reported_by', 'report_date', 'resolved_date', 'status'];

    protected $casts = [
        'report_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function computer(): BelongsTo
    {
        return $this->belongsTo(Computer::class);
    }
}
