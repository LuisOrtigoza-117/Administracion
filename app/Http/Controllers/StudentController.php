<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $grade = $request->get('grade');
        
        if ($user && $user->isTeacher()) {
            $teacherGroupIds = Group::where('teacher_id', $user->id)->pluck('id')->toArray();
            $students = Student::whereIn('group_id', $teacherGroupIds)->with('group')->orderBy('name')->get();
        } else {
            $students = Student::with('group')->orderBy('name')->get();
        }
        
        return view('students.index', compact('students', 'grade'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $grade = $request->get('grade');
        
        if ($user && $user->isTeacher()) {
            $groups = Group::where('teacher_id', $user->id)->get();
        } else {
            $groups = Group::all();
        }
        
        return view('students.create', compact('groups', 'grade'));
    }

    public function store(Request $request)
    {
        // Debug: log the incoming data
        \Log::info('Student store request:', $request->all());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'student_number' => 'required|string|max:50|unique:students',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'group_id' => 'required|exists:groups,id',
        ]);

        \Log::info('Validated data:', $validated);

        try {
            $student = Student::create($validated);
            \Log::info('Student created with ID: ' . $student->id);
            
            // Get group info for success message
            $group = Group::find($validated['group_id']);
            
            return redirect()->route('students.index')
                ->with('success', 'Estudiante "' . $student->name . ' ' . $student->lastname . '" registrado en el grupo "' . $group->name . '" exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage());
            return back()->with('error', 'Error al guardar: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Student $student)
    {
        $student->load('group', 'attendances', 'taskSubmissions', 'group.teacher');
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $user = Auth::user();
        
        if ($user && $user->isTeacher()) {
            $groups = Group::where('teacher_id', $user->id)->get();
        } else {
            $groups = Group::all();
        }
        
        return view('students.edit', compact('student', 'groups'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'student_number' => 'required|string|max:50|unique:students,student_number,' . $student->id,
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'group_id' => 'required|exists:groups,id',
        ]);

        $student->update($validated);
        return redirect()->route('students.index')->with('success', 'Estudiante actualizado exitosamente');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'Estudiante eliminado exitosamente');
    }
}
