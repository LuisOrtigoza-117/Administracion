<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', Carbon::today()->toDateString());
        $groupId = $request->get('group_id');
        
        if ($user && $user->isTeacher()) {
            $teacherGroupIds = Group::where('teacher_id', $user->id)->pluck('id')->toArray();
            $groups = Group::whereIn('id', $teacherGroupIds)->get();
        } else {
            $groups = Group::all();
            $teacherGroupIds = null;
        }
        
        $query = Attendance::with('student', 'group')->where('date', $date);
        
        if ($groupId) {
            $query->where('group_id', $groupId);
        } elseif ($teacherGroupIds) {
            $query->whereIn('group_id', $teacherGroupIds);
        }
        
        $attendances = $query->orderBy('group_id')->orderBy('student_id')->get();
        
        return view('attendances.index', compact('attendances', 'groups', 'date', 'groupId'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', Carbon::today()->toDateString());
        $groupId = $request->get('group_id');
        
        if ($user && $user->isTeacher()) {
            $teacherGroupIds = Group::where('teacher_id', $user->id)->pluck('id')->toArray();
            $groups = Group::whereIn('id', $teacherGroupIds)->get();
        } else {
            $groups = Group::all();
        }
        
        if ($groupId) {
            $students = Student::where('group_id', $groupId)->orderBy('name')->get();
        } else {
            $students = collect();
        }
        
        return view('attendances.create', compact('groups', 'date', 'groupId', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:students,id',
            'attendances.*.status' => 'required|in:present,absent,late,justified',
            'attendances.*.arrival_time' => 'nullable',
        ]);

        foreach ($validated['attendances'] as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $attendanceData['student_id'],
                    'group_id' => $validated['group_id'],
                    'date' => $validated['date'],
                ],
                [
                    'status' => $attendanceData['status'],
                    'arrival_time' => $attendanceData['arrival_time'] ?? null,
                ]
            );
        }

        return redirect()->route('attendances.index', ['date' => $validated['date'], 'group_id' => $validated['group_id']])
            ->with('success', 'Asistencia guardada exitosamente');
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        $selectedGroupIds = $request->get('group_ids', []);
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        
        if ($user && $user->isTeacher()) {
            $teacherGroupIds = Group::where('teacher_id', $user->id)->pluck('id')->toArray();
            $groups = Group::whereIn('id', $teacherGroupIds)->get();
        } else {
            $groups = Group::all();
            $teacherGroupIds = null;
        }
        
        $query = Attendance::with('student')->whereBetween('date', [$startDate, $endDate]);
        
        if (!empty($selectedGroupIds)) {
            $query->whereIn('group_id', $selectedGroupIds);
            $students = Student::whereIn('group_id', $selectedGroupIds)->get();
        } elseif ($teacherGroupIds) {
            $query->whereIn('group_id', $teacherGroupIds);
            $students = Student::whereIn('group_id', $teacherGroupIds)->get();
        } else {
            $students = Student::all();
        }
        
        $attendances = $query->get();
        
        return view('attendances.report', compact('attendances', 'groups', 'selectedGroupIds', 'startDate', 'endDate', 'students'));
    }
}
