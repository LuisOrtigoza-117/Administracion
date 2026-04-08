<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Schedule;
use App\Models\FixedTeacherSchedule;
use App\Models\Recess;
use App\Models\User;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $groupId = $request->get('group_id');
        $grade = $request->get('grade');
        
        $groups = Group::orderBy('grade')->orderBy('name')->get();
        
        $query = Schedule::with('group', 'teacher');
        
        if ($groupId) {
            $query->where('group_id', $groupId);
        }
        
        if ($grade) {
            $groupIds = Group::where('grade', $grade)->pluck('id');
            $query->whereIn('group_id', $groupIds);
        }
        
        $schedules = $query->orderBy('day')->orderBy('hour_number')->get();
        
        $fixedSchedules = FixedTeacherSchedule::with('teacher')->get();
        
        return view('schedules.index', compact('schedules', 'groups', 'groupId', 'grade', 'fixedSchedules'));
    }

    public function create(Request $request)
    {
        $groupId = $request->get('group_id');
        $groups = Group::orderBy('grade')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $fixedTeachers = FixedTeacherSchedule::with('teacher')->get();
        $timeSlots = TimeSlot::getActiveSlots();
        
        $selectedGroup = $groupId ? Group::find($groupId) : null;
        
        return view('schedules.create', compact('groups', 'teachers', 'fixedTeachers', 'groupId', 'selectedGroup', 'timeSlots'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required',
            'hour_number' => 'required|integer|min:1',
            'start_time' => 'required',
            'end_time' => 'required',
            'subject' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'is_recess' => 'nullable',
        ]);

        $isRecess = !empty($validated['is_recess']) && ($validated['is_recess'] == '1' || $validated['is_recess'] == 'all');
        $isRecessAll = !empty($validated['is_recess']) && $validated['is_recess'] == 'all';

        if ($isRecess) {
            $validated['subject'] = 'Receso';
            $validated['teacher_id'] = null;

            if ($isRecessAll) {
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                $created = 0;
                foreach ($days as $day) {
                    Schedule::create([
                        'group_id' => $validated['group_id'],
                        'day' => $day,
                        'hour_number' => $validated['hour_number'],
                        'start_time' => $validated['start_time'],
                        'end_time' => $validated['end_time'],
                        'subject' => 'Receso',
                        'teacher_id' => null,
                        'is_recess' => true,
                    ]);
                    $created++;
                }
                return redirect()->route('schedules.index', ['group_id' => $validated['group_id']])
                    ->with('success', "Recreo agregado en $created días exitosamente");
            } else {
                $validated['is_recess'] = true;
                Schedule::create($validated);
                return redirect()->route('schedules.index', ['group_id' => $validated['group_id']])
                    ->with('success', 'Recreo agregado exitosamente');
            }
        } else {
            $validated['is_recess'] = false;
            
            if (empty($validated['day']) || $validated['day'] === '') {
                return back()->with('error', 'Selecciona un día para la clase');
            }
            
            if ($validated['teacher_id']) {
                if (Schedule::checkConflict($validated['teacher_id'], $validated['day'], $validated['hour_number'])) {
                    return back()->with('error', 'El profesor ya tiene una clase asignada en ese horario');
                }
            }

            Schedule::create($validated);
            return redirect()->route('schedules.index', ['group_id' => $validated['group_id']])
                ->with('success', 'Horario creado exitosamente');
        }
    }

    public function edit(Schedule $schedule)
    {
        $groups = Group::orderBy('grade')->orderBy('name')->get();
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $fixedTeachers = FixedTeacherSchedule::with('teacher')->get();
        $timeSlots = TimeSlot::getActiveSlots();
        
        return view('schedules.edit', compact('schedule', 'groups', 'teachers', 'fixedTeachers', 'timeSlots'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'day' => 'required',
            'hour_number' => 'required|integer|min:1',
            'start_time' => 'required',
            'end_time' => 'required',
            'subject' => 'nullable|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
            'is_recess' => 'nullable',
        ]);

        $isRecess = !empty($validated['is_recess']) && $validated['is_recess'] == '1';

        if ($isRecess) {
            $validated['is_recess'] = true;
            $validated['subject'] = 'Receso';
            $validated['teacher_id'] = null;
        } else {
            $validated['is_recess'] = false;
            
            if (empty($validated['subject'])) {
                return back()->with('error', 'La materia es requerida para clases');
            }
            
            if ($validated['teacher_id']) {
                if (Schedule::checkConflict($validated['teacher_id'], $validated['day'], $validated['hour_number'], $schedule->id)) {
                    return back()->with('error', 'El profesor ya tiene una clase asignada en ese horario');
                }
                
                $fixedSchedule = FixedTeacherSchedule::where('teacher_id', $validated['teacher_id'])
                    ->where('day', $validated['day'])
                    ->where('hour_number', $validated['hour_number'])
                    ->first();
                    
                if ($fixedSchedule) {
                    return back()->with('error', 'El profesor tiene horario fijo en ese horario y no puede ser movido');
                }
            }
        }

        $schedule->update($validated);
        return redirect()->route('schedules.index', ['group_id' => $validated['group_id']])
            ->with('success', 'Horario actualizado exitosamente');
    }

    public function destroy(Schedule $schedule)
    {
        $groupId = $schedule->group_id;
        $schedule->delete();
        return redirect()->route('schedules.index', ['group_id' => $groupId])
            ->with('success', 'Horario eliminado exitosamente');
    }

    public function fixedTeachers()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $fixedSchedules = FixedTeacherSchedule::with('teacher')->orderBy('day')->orderBy('hour_number')->get();
        $timeSlots = TimeSlot::getActiveSlots();
        
        return view('schedules.fixed-teachers', compact('teachers', 'fixedSchedules', 'timeSlots'));
    }

    public function storeFixedTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'hour_number' => 'required|integer|min:1|max:7',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'subject' => 'required|string|max:255',
            'grade_group' => 'required|string|max:50',
        ]);

        if (FixedTeacherSchedule::checkConflict($validated['teacher_id'], $validated['day'], $validated['hour_number'])) {
            return back()->with('error', 'Este maestro ya tiene horario fijo en ese horario');
        }

        FixedTeacherSchedule::create($validated);
        return redirect()->route('schedules.fixed-teachers')
            ->with('success', 'Horario fijo creado exitosamente');
    }

    public function destroyFixedTeacher(FixedTeacherSchedule $fixedTeacher)
    {
        $fixedTeacher->delete();
        return redirect()->route('schedules.fixed-teachers')
            ->with('success', 'Horario fijo eliminado');
    }

    public function printSchedule(Request $request)
    {
        $grade = $request->get('grade');
        $selectedGroupIds = $request->get('group_ids', []);
        
        $allGroups = Group::orderBy('grade')->orderBy('name')->get();
        
        if ($grade) {
            $groups = $allGroups->where('grade', $grade);
        } else {
            $groups = $allGroups;
        }
        
        if (!empty($selectedGroupIds)) {
            $groups = $groups->whereIn('id', $selectedGroupIds);
        }
        
        $schedules = Schedule::with('group', 'teacher')
            ->whereIn('group_id', $groups->pluck('id'))
            ->orderBy('day')
            ->orderBy('hour_number')
            ->get();
        
        $fixedSchedules = FixedTeacherSchedule::with('teacher')->get();
        $timeSlots = TimeSlot::getActiveSlots();
        
        return view('schedules.print', compact('groups', 'schedules', 'fixedSchedules', 'grade', 'timeSlots', 'selectedGroupIds'));
    }

    public function generateDefaultHours()
    {
        $hours = [
            1 => ['start' => '07:00', 'end' => '07:50'],
            2 => ['start' => '07:50', 'end' => '08:40'],
            3 => ['start' => '08:40', 'end' => '09:30'],
            4 => ['start' => '09:30', 'end' => '10:20'],
            5 => ['start' => '10:20', 'end' => '10:40'], // Receso
            6 => ['start' => '10:40', 'end' => '11:30'],
            7 => ['start' => '11:30', 'end' => '12:20'],
            8 => ['start' => '12:20', 'end' => '13:10'],
            9 => ['start' => '13:10', 'end' => '14:00'],
            10 => ['start' => '14:00', 'end' => '14:10'], // Comida
        ];
        
        return $hours;
    }

    public function getScheduleByGroup($groupId)
    {
        $group = Group::findOrFail($groupId);
        $schedules = Schedule::where('group_id', $groupId)
            ->orderBy('day')
            ->orderBy('hour_number')
            ->get();
        
        return view('schedules.group-view', compact('group', 'schedules'));
    }
}
