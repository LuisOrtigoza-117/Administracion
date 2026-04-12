<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm($type = null)
    {
        if (!in_array($type, ['student', 'teacher'])) {
            return redirect()->route('login.form');
        }

        $groups = Group::all();
        return view('auth.register', compact('type', 'groups'));
    }

    public function register(Request $request, $type)
    {
        if (!in_array($type, ['student', 'teacher'])) {
            return redirect()->route('login.form');
        }

        if ($type === 'student') {
            return $this->registerStudent($request);
        }

        return $this->registerTeacher($request);
    }

    protected function registerStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'student_number' => 'required|string|max:20|unique:students,student_number',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'group_id' => 'required|exists:groups,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student = Student::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'student_number' => $request->student_number,
            'email' => $request->email,
            'group_id' => $request->group_id,
        ]);

        $user = User::create([
            'name' => $request->name . ' ' . $request->lastname,
            'email' => $request->email ?? $request->student_number . '@school.local',
            'password' => Hash::make($request->password),
            'role' => 'student',
            'student_id' => $student->id,
        ]);

        auth()->login($user);

        return redirect()->route('student.dashboard')->with('success', 'Registro exitoso. Bienvenido, ' . $student->name);
    }

    protected function registerTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'phone' => $request->phone,
            'specialty' => $request->specialty,
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Registro exitoso. Bienvenido, ' . $user->name);
    }
}
