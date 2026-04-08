<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'name',
        'hour_number',
        'start_time',
        'end_time',
        'duration_minutes',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    public static function getDefaultSlots()
    {
        return [
            ['name' => '1ª Hora', 'hour_number' => 1, 'start_time' => '07:00', 'end_time' => '07:50', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => '2ª Hora', 'hour_number' => 2, 'start_time' => '07:50', 'end_time' => '08:40', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => '3ª Hora', 'hour_number' => 3, 'start_time' => '08:40', 'end_time' => '09:30', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => '4ª Hora', 'hour_number' => 4, 'start_time' => '09:30', 'end_time' => '10:20', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => 'Receso 1', 'hour_number' => 5, 'start_time' => '10:20', 'end_time' => '10:40', 'duration_minutes' => 20, 'type' => 'recess'],
            ['name' => '5ª Hora', 'hour_number' => 6, 'start_time' => '10:40', 'end_time' => '11:30', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => '6ª Hora', 'hour_number' => 7, 'start_time' => '11:30', 'end_time' => '12:20', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => '7ª Hora', 'hour_number' => 8, 'start_time' => '12:20', 'end_time' => '13:10', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => '8ª Hora', 'hour_number' => 9, 'start_time' => '13:10', 'end_time' => '14:00', 'duration_minutes' => 50, 'type' => 'class'],
            ['name' => 'Receso 2', 'hour_number' => 10, 'start_time' => '14:00', 'end_time' => '14:15', 'duration_minutes' => 15, 'type' => 'recess'],
            ['name' => 'Comida', 'hour_number' => 11, 'start_time' => '14:15', 'end_time' => '15:00', 'duration_minutes' => 45, 'type' => 'lunch'],
        ];
    }

    public static function initializeDefaults()
    {
        self::where('is_active', true)->delete();
        
        $slots = self::getDefaultSlots();
        foreach ($slots as $slot) {
            self::create($slot);
        }
    }

    public static function getActiveSlots()
    {
        return self::where('is_active', true)
            ->orderBy('hour_number')
            ->get();
    }

    public static function getRecesses()
    {
        return self::where('type', 'recess')
            ->where('is_active', true)
            ->orderBy('hour_number')
            ->get();
    }
}
