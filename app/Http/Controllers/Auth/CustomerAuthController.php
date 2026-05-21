<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    // === Standard Login & Register ===

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

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->route('customer.dashboard');
        }

        RateLimiter::hit($throttleKey, 300); // Fail count increments, locked out for 5 mins if threshold met

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|regex:/^[a-zA-Z0-9\._%+-]+@gmail\.com$/|unique:customers',
            'phone' => 'required|string|regex:/^[0-9]+$/|unique:customers',
            'password' => 'required|min:6|confirmed'
        ], [
            'name.regex' => 'The name may only contain letters and spaces.',
            'email.regex' => 'The email must be a valid @gmail.com address.',
            'phone.regex' => 'The phone number may only contain digits.',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'active'
        ]);

        Auth::guard('customer')->login($customer, $request->boolean('remember'));
        return redirect()->route('customer.onboarding');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('view.login.customer');
    }
}
