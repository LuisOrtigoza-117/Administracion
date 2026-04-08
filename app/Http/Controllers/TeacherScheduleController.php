<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Group;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isTeacher()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado');
        }
        
        $schedules = Schedule::where('teacher_id', $user->id)
            ->with(['group', 'group.teacher'])
            ->orderBy('day')
            ->orderBy('hour_number')
            ->get();
        
        $groupIds = $schedules->pluck('group_id')->unique();
        $groups = Group::whereIn('id', $groupIds)->with('teacher')->get();
        
        $timeSlots = TimeSlot::getActiveSlots();
        
        $days = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes', 
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes'
        ];
        
        return view('teacher-schedules.index', compact('schedules', 'groups', 'timeSlots', 'days'));
    }
    
    public function print()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isTeacher()) {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado');
        }
        
        $schedules = Schedule::where('teacher_id', $user->id)
            ->with(['group', 'group.teacher'])
            ->orderBy('day')
            ->orderBy('hour_number')
            ->get();
        
        $groupIds = $schedules->pluck('group_id')->unique();
        $groups = Group::whereIn('id', $groupIds)->with('teacher')->get();
        
        $timeSlots = TimeSlot::getActiveSlots();
        
        $days = [
            'monday' => 'LUNES',
            'tuesday' => 'MARTES', 
            'wednesday' => 'MIERCOLES',
            'thursday' => 'JUEVES',
            'friday' => 'VIERNES'
        ];
        
        return view('teacher-schedules.print', compact('schedules', 'groups', 'timeSlots', 'days', 'user'));
    }
}
