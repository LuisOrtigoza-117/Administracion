<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')->orderBy('name')->get();
        $students = User::where('role', 'student')->with('student.group')->orderBy('name')->get();
        return view('users.index', compact('teachers', 'students'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('users.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:teacher,student',
            'student_number' => 'nullable|string',
            'lastname' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        if ($request->role === 'student') {
            $student = Student::create([
                'name' => $request->name,
                'lastname' => $request->lastname ?? '',
                'student_number' => $request->student_number,
                'email' => $request->email,
                'group_id' => $request->group_id,
            ]);
            
            $userData['student_id'] = $student->id;
        }

        User::create($userData);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $groups = Group::all();
        $student = $user->student;
        return view('users.edit', compact('user', 'groups', 'student'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:teacher,student',
            'student_number' => 'nullable|string',
            'lastname' => 'nullable|string',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->password) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->role === 'student') {
            $studentData = [
                'name' => $request->name,
                'lastname' => $request->lastname ?? '',
                'student_number' => $request->student_number,
                'email' => $request->email,
                'group_id' => $request->group_id,
            ];

            if ($user->student) {
                $user->student->update($studentData);
            } else {
                $student = Student::create($studentData);
                $userData['student_id'] = $student->id;
            }
        } else {
            if ($user->student) {
                $user->student->delete();
                $userData['student_id'] = null;
            }
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $isCurrentUser = Auth::id() === $user->id;
        
        if ($user->student) {
            $user->student->delete();
        }
        $user->delete();

        if ($isCurrentUser) {
            Auth::logout();
            return redirect('/')->with('success', 'Tu cuenta ha sido eliminada.');
        }

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
