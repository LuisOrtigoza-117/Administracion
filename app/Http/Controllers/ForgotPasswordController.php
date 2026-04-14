<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Generate reset token
        $token = Str::random(64);
        
        // Delete any existing tokens for this user
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // In production, send email with reset link
        // For now, we'll redirect to reset form directly with token
        
        return redirect()->route('password.reset', ['token' => $token, 'email' => $request->email])
            ->with('success', 'Token de recuperación generado. Establece tu nueva contraseña.');
    }

    public function showResetForm(Request $request, $token = null)
    {
        if (!$token || !$request->email) {
            return redirect()->route('password.request')
                ->with('error', 'Token o email inválido.');
        }
        
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.exists' => 'No existe un usuario con ese correo electrónico.',
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return redirect()->route('password.request')
                ->with('error', 'El enlace de recuperación es inválido o ha sido usado. Solicita uno nuevo.');
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'No existe un usuario con ese correo electrónico.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login.form')
            ->with('success', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
    }
}
