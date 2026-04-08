<?php

namespace App\Http\Controllers;

use App\Models\Recess;
use App\Models\Group;
use Illuminate\Http\Request;

class RecessController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'name' => 'nullable|string|max:50',
        ]);

        if (Recess::where('group_id', $validated['group_id'])
            ->where('day', $validated['day'])
            ->exists()) {
            return back()->with('error', 'Este grupo ya tiene un receso asignado para este día');
        }

        $validated['name'] = $validated['name'] ?: 'Receso';
        
        Recess::create($validated);
        return redirect()->route('schedules.index')->with('success', 'Receso creado exitosamente');
    }

    public function destroy(Recess $recess)
    {
        $recess->delete();
        return redirect()->route('schedules.index')->with('success', 'Receso eliminado');
    }
}
