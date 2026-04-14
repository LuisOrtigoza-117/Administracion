<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Group;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        if ($user->isStudent() && $user->student) {
            $user->load('student.group.teacher');
            return view('profile.student', compact('user'));
        }
        
        $groups = Group::with(['students', 'tasks'])->where('teacher_id', $user->id)->get();
        $totalStudents = $groups->sum(fn($g) => $g->students->count());
        
        return view('profile.teacher', compact('user', 'groups', 'totalStudents'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($user->isTeacher()) {
            if ($request->has('phone')) {
                $userData['phone'] = $request->phone;
            }
            if ($request->has('specialty')) {
                $userData['specialty'] = $request->specialty;
            }
        }

        $user->update($userData);

        if ($user->isStudent() && $user->student) {
            $studentData = [];
            
            if ($request->lastname !== null && $request->lastname !== '') {
                $studentData['lastname'] = $request->lastname;
            }
            if ($request->student_number !== null && $request->student_number !== '') {
                $studentData['student_number'] = $request->student_number;
            }
            if ($request->phone !== null && $request->phone !== '') {
                $studentData['phone'] = $request->phone;
            }
            
            if (!empty($studentData)) {
                $user->student->update($studentData);
            }
        }

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Contraseña cambiada correctamente.');
    }
}
