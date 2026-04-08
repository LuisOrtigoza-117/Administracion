<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user && $user->isTeacher()) {
            $groups = Group::where('teacher_id', $user->id)->orderBy('grade')->orderBy('section')->get();
        } else {
            $groups = Group::orderBy('grade')->orderBy('section')->get();
        }
        
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('groups.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
            'section' => 'required|string|max:50',
            'school_year' => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        Group::create($validated);
        return redirect()->route('groups.index')->with('success', 'Grupo creado exitosamente');
    }

    public function show(Group $group)
    {
        $group->load('students', 'tasks', 'teacher');
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        return view('groups.edit', compact('group', 'teachers'));
    }

    public function update(Request $request, Group $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade' => 'required|string|max:50',
            'section' => 'required|string|max:50',
            'school_year' => 'required|string|max:50',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $group->update($validated);
        return redirect()->route('groups.index')->with('success', 'Grupo actualizado exitosamente');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('groups.index')->with('success', 'Grupo eliminado exitosamente');
    }
}
