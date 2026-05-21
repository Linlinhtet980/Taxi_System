<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|regex:/^[a-zA-Z0-9\._%+-]+@gmail\.com$/',
            'password' => 'required'
        ], [
            'email.regex' => 'The email must be a valid @gmail.com address.',
        ]);

        $credentials = $request->only('email', 'password');
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        // Check if locked out (5 fail limit, locked for 5 minutes)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.',
            ])->withInput($request->only('email'));
        }

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->route('dashboard.home');
        }

        RateLimiter::hit($throttleKey, 300); // 5 mins lockout

        return back()->withErrors([
            'email' => 'The provided administrator credentials do not match our secure records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('view.login.admin');
    }
}
