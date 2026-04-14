<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Attendance;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect('/')->with('error', 'No tienes un perfil de estudiante asociado.');
        }
        
        $student->load('group.teacher');
        $studentGroupId = $student->group_id;
        
        $tasks = Task::where(function($query) use ($studentGroupId) {
            $query->whereJsonContains('group_ids', $studentGroupId);
        })->with(['submissions' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }])->latest()->take(5)->get();

        $recentAttendances = Attendance::where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact('student', 'tasks', 'recentAttendances'));
    }

    public function tasks()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect('/')->with('error', 'No tienes un perfil de estudiante asociado.');
        }

        $studentGroupId = $student->group_id;
        
        $tasks = Task::where(function($query) use ($studentGroupId) {
            $query->whereJsonContains('group_ids', $studentGroupId);
        })->with(['submissions' => function ($query) use ($student) {
            $query->where('student_id', $student->id);
        }])->latest()->get();

        return view('student.tasks', compact('tasks', 'student'));
    }

    public function attendance()
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect('/')->with('error', 'No tienes un perfil de estudiante asociado.');
        }

        $attendances = Attendance::where('student_id', $student->id)
            ->with('group')
            ->latest()
            ->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateDays = $attendances->where('status', 'late')->count();

        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

        return view('student.attendance', compact('attendances', 'student', 'totalDays', 'presentDays', 'absentDays', 'lateDays', 'attendancePercentage'));
    }

    public function submitTask(Request $request, Task $task)
    {
        $user = Auth::user();
        $student = $user->student;
        
        if (!$student) {
            return redirect('/')->with('error', 'No tienes un perfil de estudiante asociado.');
        }

        $request->validate([
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,jpg,jpeg,png,gif,bmp|max:20480',
        ], [
            'file.mimes' => 'El archivo debe ser un documento PDF, Word, Excel, PowerPoint, imagen o archivo comprimido.',
            'file.max' => 'El archivo no puede superar los 20MB.',
        ]);

        $existingSubmission = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return back()->with('error', 'Ya has enviado una tarea anteriormente.');
        }

        $filePath = null;
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $student->id . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('submissions', $fileName, 'public');
        }

        TaskSubmission::create([
            'task_id' => $task->id,
            'student_id' => $student->id,
            'content' => $request->content ?? 'Entrega de tarea',
            'file_path' => $filePath,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Tarea enviada correctamente.');
    }
}
