<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\Driver;
use App\Models\Core\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class DriverAuthController extends Controller
{
    // === Login ===

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('driver')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $driver = Auth::guard('driver')->user();
            
            // Onboarding Check
            return $this->checkOnboardingStatus($driver);
        }

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
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers',
            'password' => 'required|min:6|confirmed'
        ]);

        $driver = Driver::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'driver_status' => 'pending',
            'wallet_balance' => 0,
            'loyalty_points' => 0
        ]);

        Auth::guard('driver')->login($driver);
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
            'phone_no' => 'required|string|unique:drivers,phone_no,' . $driver->id,
            'emergency_contact_no' => 'required|string',
            'license_no' => 'required|string|unique:drivers,license_no,' . $driver->id,
            'identity_card_no' => 'required|string',
            'address' => 'required|string',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'license_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'license_photo_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nric_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nric_photo_back' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'brand' => 'required|string',
            'model' => 'required|string',
            'license_plate' => 'required|string|unique:vehicles,license_plate',
            'vehicle_type' => 'required|string',
            'color' => 'required|string',
            'year' => 'required|integer',
            'mileage' => 'required|integer',
            'vehicle_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'front_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'back_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'left_side_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'right_side_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'interior_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'last_maintenance_at' => 'nullable|date',
            'next_maintenance_at' => 'nullable|date',
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
