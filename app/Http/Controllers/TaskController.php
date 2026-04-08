<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $groupId = $request->get('group_id');
        
        if ($user && $user->isTeacher()) {
            $teacherGroupIds = Group::where('teacher_id', $user->id)->pluck('id')->toArray();
            $groups = Group::whereIn('id', $teacherGroupIds)->get();
        } else {
            $groups = Group::all();
            $teacherGroupIds = null;
        }
        
        $query = Task::query();
        
        if ($groupId) {
            $query->whereJsonContains('group_ids', (int)$groupId);
        } elseif ($teacherGroupIds) {
            $query->where(function($q) use ($teacherGroupIds) {
                foreach ($teacherGroupIds as $id) {
                    $q->orWhereJsonContains('group_ids', $id);
                }
            });
        }
        
        $tasks = $query->orderBy('due_date', 'desc')->get();
        
        return view('tasks.index', compact('tasks', 'groups', 'groupId'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user && $user->isTeacher()) {
            $groups = Group::where('teacher_id', $user->id)->get();
        } else {
            $groups = Group::all();
        }
        
        return view('tasks.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_points' => 'required|numeric|min:0',
            'attachments.*' => 'nullable|file|max:20480',
        ]);

        $attachments = [];
        
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('task-attachments', $fileName, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $validated['attachments'] = $attachments;
        $validated['group_ids'] = array_map('intval', $validated['group_ids']);

        Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Tarea creada exitosamente');
    }

    public function show(Task $task)
    {
        $task->load('submissions.student');
        
        // Get students from all groups assigned to this task
        $groupIds = $task->group_ids ?? [];
        $students = Student::whereIn('group_id', $groupIds)->get();
        
        return view('tasks.show', compact('task', 'students'));
    }

    public function edit(Task $task)
    {
        $user = Auth::user();
        
        if ($user && $user->isTeacher()) {
            $groups = Group::where('teacher_id', $user->id)->get();
        } else {
            $groups = Group::all();
        }
        
        return view('tasks.edit', compact('task', 'groups'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'group_ids' => 'required|array|min:1',
            'group_ids.*' => 'required|exists:groups,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_points' => 'required|numeric|min:0',
            'attachments.*' => 'nullable|file|max:20480',
        ]);

        $attachments = $task->attachments ?? [];
        
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('task-attachments', $fileName, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $validated['attachments'] = $attachments;
        $validated['group_ids'] = array_map('intval', $validated['group_ids']);

        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Tarea actualizada exitosamente');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Tarea eliminada exitosamente');
    }

    public function submit(Request $request, Task $task)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'content' => 'nullable|string',
        ]);

        $existingSubmission = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $validated['student_id'])
            ->first();

        if ($existingSubmission) {
            return back()->with('error', 'Este estudiante ya ha entregado esta tarea.');
        }

        TaskSubmission::create([
            'task_id' => $task->id,
            'student_id' => $validated['student_id'],
            'content' => $validated['content'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Tarea entregada exitosamente');
    }

    public function grade(Request $request, TaskSubmission $submission)
    {
        $validated = $request->validate([
            'grade' => 'required|numeric|min:0',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'],
            'status' => 'graded',
        ]);

        return back()->with('success', 'Calificación guardada exitosamente');
    }
}
