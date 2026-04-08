<?php

namespace App\Http\Controllers;

use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $slots = TimeSlot::orderBy('hour_number')->get();
        return view('schedules.time-slots', compact('slots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hour_number' => 'required|integer|min:1|max:20',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'type' => 'required|in:class,recess,lunch',
        ]);

        TimeSlot::create($validated);
        return redirect()->route('schedules.time-slots')
            ->with('success', 'Módulo de tiempo creado exitosamente');
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hour_number' => 'required|integer|min:1|max:20',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration_minutes' => 'required|integer|min:1',
            'type' => 'required|in:class,recess,lunch',
            'is_active' => 'boolean',
        ]);

        $timeSlot->update($validated);
        return redirect()->route('schedules.time-slots')
            ->with('success', 'Módulo de tiempo actualizado');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('schedules.time-slots')
            ->with('success', 'Módulo eliminado');
    }

    public function initializeDefaults()
    {
        TimeSlot::initializeDefaults();
        return redirect()->route('schedules.time-slots')
            ->with('success', 'Horarios inicializados con valores predeterminados');
    }
}
