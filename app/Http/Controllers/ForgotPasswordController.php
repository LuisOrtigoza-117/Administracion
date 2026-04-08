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
        if (!$token) {
            return redirect()->route('password.request')
                ->with('error', 'Token de recuperación requerido.');
        }
        
        return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()->with('error', 'Token de recuperación inválido.');
        }

        // Check if token is expired (24 hours)
        if ($passwordReset->created_at->diffInHours(now()) > 24) {
            return back()->with('error', 'El token ha expirado. Solicita uno nuevo.');
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
    }
}
