<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $userType = $request->input('user_type', 'student');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($userType === 'student' && !$user->isStudent()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Este usuario no es un estudiante.',
                ])->onlyInput('email');
            }
            
            if ($userType === 'teacher' && $user->isStudent()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Este usuario no es un maestro.',
                ])->onlyInput('email');
            }
            
            if ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
