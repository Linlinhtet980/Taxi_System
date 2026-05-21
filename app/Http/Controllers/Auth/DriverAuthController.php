<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Driver;
use App\Models\Core\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class DriverAuthController extends Controller
{
    // === Login ===

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

        if (Auth::guard('driver')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $driver = Auth::guard('driver')->user();
            
            // Onboarding Check
            return $this->checkOnboardingStatus($driver);
        }

        RateLimiter::hit($throttleKey, 300); // 5 mins lockout

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    private function checkOnboardingStatus(Driver $driver)
    {
        // 1. Check if personal info (phone) is missing
        if (!$driver->phone_no) {
            return redirect()->route('driver.onboarding.personal');
        }

        // 2. Check if vehicle info is missing
        $vehicle = Vehicle::query()->where('driver_id', $driver->id)->first();
        if (!$vehicle) {
            return redirect()->route('driver.onboarding.vehicle');
        }

        // 3. All complete -> Dashboard
        return redirect()->route('driver.dashboard', $driver->id);
    }

    // === Initial Registration ===

    public function registerInitial(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|regex:/^[a-zA-Z0-9\._%+-]+@gmail\.com$/|unique:drivers',
            'password' => 'required|min:6|confirmed'
        ], [
            'full_name.regex' => 'The full name may only contain letters and spaces.',
            'email.regex' => 'The email must be a valid @gmail.com address.',
        ]);

        $driver = Driver::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'driver_status' => 'pending',
            'wallet_balance' => 0,
            'loyalty_points' => 0
        ]);

        Auth::guard('driver')->login($driver, $request->boolean('remember'));
        return redirect()->route('driver.onboarding.personal');
    }

    // === Onboarding Step 1: Personal ===

    public function onboardingPersonal()
    {
        $driver = Auth::guard('driver')->user();
        return view('driverview.onboarding.personal', compact('driver'));
    }

    public function onboardingPersonalStore(Request $request)
    {
        $driver = Auth::guard('driver')->user();
        
        $request->validate([
            'phone_no' => 'required|string|regex:/^[0-9]+$/|min:7|max:11|unique:drivers,phone_no,' . $driver->id,
            'emergency_contact_no' => 'nullable|string|regex:/^[0-9]+$/|min:7|max:11',
            'license_no' => 'required|string|regex:/^[a-zA-Z0-9\/]+$/|unique:drivers,license_no,' . $driver->id,
            'identity_card_no' => 'required|string|regex:/^\d{1,2}\/[a-zA-Z]+\([Nn]\)\d{6}$/|unique:drivers,identity_card_no,' . $driver->id,
            'address' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'license_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'license_photo_back' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nric_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'nric_photo_back' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'phone_no.regex' => 'The phone number must contain only digits.',
            'emergency_contact_no.regex' => 'The emergency contact phone number must contain only digits.',
            'license_no.regex' => 'The license number must contain only letters, numbers, and slashes.',
            'identity_card_no.regex' => 'The NRIC number must match the Myanmar NRIC format (e.g. 12/LATHANA(N)123456).',
            'profile_picture.max' => 'The profile picture size must not exceed 2MB.',
            'license_photo.max' => 'The license photo size must not exceed 2MB.',
            'nric_photo.max' => 'The NRIC photo size must not exceed 2MB.',
        ]);

        $data = $request->only(['phone_no', 'emergency_contact_no', 'license_no', 'identity_card_no', 'address']);

        // Handle File Uploads
        $fileFields = ['profile_picture', 'license_photo', 'license_photo_back', 'nric_photo', 'nric_photo_back'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/drivers'), $filename);
                $data[$field] = 'uploads/drivers/' . $filename;
            }
        }

        $driver->update($data);

        return redirect()->route('driver.onboarding.vehicle');
    }

    // === Onboarding Step 2: Vehicle ===

    public function onboardingVehicle()
    {
        $driver = Auth::guard('driver')->user();
        return view('driverview.onboarding.vehicle', compact('driver'));
    }

    public function onboardingVehicleStore(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'nullable|string|max:255',
            'license_plate' => 'required|string|regex:/^[a-zA-Z0-9\s\-\/]+$/|unique:vehicles,license_plate',
            'vehicle_type' => 'required|string',
            'color' => 'required|string|max:100',
            'year' => 'required|integer|between:2000,2026',
            'mileage' => 'required|integer|min:0',
            'vehicle_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'front_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'interior_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'back_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'left_side_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'right_side_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'last_maintenance_at' => 'required|date|before_or_equal:today',
            'next_maintenance_at' => 'nullable|date',
        ], [
            'license_plate.regex' => 'The plate number format is invalid (letters, numbers, spaces, hyphens, and slashes only).',
            'year.between' => 'The vehicle year must be between 2000 and 2026.',
            'mileage.min' => 'The mileage must be 0 or greater.',
            'last_maintenance_at.before_or_equal' => 'The last maintenance date cannot be in the future.',
            'vehicle_photo.max' => 'The main view photo size must not exceed 2MB.',
            'front_photo.max' => 'The front view photo size must not exceed 2MB.',
            'interior_photo.max' => 'The interior view photo size must not exceed 2MB.',
        ]);

        $data = $request->only([
            'brand', 'model', 'license_plate', 'vehicle_type', 
            'color', 'year', 'mileage', 'last_maintenance_at', 'next_maintenance_at'
        ]);
        $data['driver_id'] = $driver->id;
        $data['status'] = 'Available';

        // Handle File Uploads
        $fileFields = ['vehicle_photo', 'front_photo', 'back_photo', 'left_side_photo', 'right_side_photo', 'interior_photo'];
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/vehicles'), $filename);
                $data[$field] = 'uploads/vehicles/' . $filename;
            }
        }

        Vehicle::create($data);

        return redirect()->route('driver.dashboard', $driver->id)->with('success', 'Profile setup complete!');
    }

    public function logout(Request $request)
    {
        Auth::guard('driver')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('view.login.driver');
    }
}
