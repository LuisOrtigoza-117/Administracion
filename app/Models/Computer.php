<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Computer extends Model
{
    protected $fillable = [
        'pc_number', 'location', 'status',
        'brand', 'model', 'processor', 'ram', 'storage', 'operating_system',
        'monitor', 'keyboard', 'mouse', 'notes', 'purchase_date'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(ComputerReport::class);
    }
}
