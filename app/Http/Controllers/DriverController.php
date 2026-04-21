<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::latest()->paginate(5);
        $totalActive = Driver::where('driver_status', 'active')->count();
        $totalPending = Driver::where('driver_status', 'pending')->count();
        
        return view('dashboardview.driver.index', compact('drivers', 'totalActive', 'totalPending'));
    }

    public function create()
    {
        return view('dashboardview.driver.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'nullable|email|unique:drivers,email',
            'phone_no' => 'required|string|unique:drivers,phone_no',
            'emergency_contact_no' => 'nullable|string',
            'license_no' => 'required|string|unique:drivers,license_no',
            'vehicle_no' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'driver_status' => 'required|in:active,inactive,pending',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nric_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'identity_card_no' => 'nullable|string',
        ]);

        $data = $validated;

        // Handle File Uploads
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('drivers/profiles', 'public');
        }
        if ($request->hasFile('license_photo')) {
            $data['license_photo'] = $request->file('license_photo')->store('drivers/licenses', 'public');
        }
        if ($request->hasFile('nric_photo')) {
            $data['nric_photo'] = $request->file('nric_photo')->store('drivers/nric', 'public');
        }

        Driver::create($data);

        return redirect()->route('drivers.index')->with('success', 'Driver registered successfully!');
    }

    public function edit(Driver $driver)
    {
        return view('dashboardview.driver.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'email' => 'nullable|email|unique:drivers,email,' . $driver->id,
            'phone_no' => 'required|string|unique:drivers,phone_no,' . $driver->id,
            'emergency_contact_no' => 'nullable|string',
            'license_no' => 'required|string|unique:drivers,license_no,' . $driver->id,
            'vehicle_no' => 'nullable|string',
            'vehicle_type' => 'nullable|string',
            'driver_status' => 'required|in:active,inactive,pending',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'license_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nric_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'identity_card_no' => 'nullable|string',
        ]);

        $data = $validated;

        // Handle File Uploads & Delete Old Files
        if ($request->hasFile('profile_picture')) {
            if ($driver->profile_picture) Storage::disk('public')->delete($driver->profile_picture);
            $data['profile_picture'] = $request->file('profile_picture')->store('drivers/profiles', 'public');
        }
        if ($request->hasFile('license_photo')) {
            if ($driver->license_photo) Storage::disk('public')->delete($driver->license_photo);
            $data['license_photo'] = $request->file('license_photo')->store('drivers/licenses', 'public');
        }
        if ($request->hasFile('nric_photo')) {
            if ($driver->nric_photo) Storage::disk('public')->delete($driver->nric_photo);
            $data['nric_photo'] = $request->file('nric_photo')->store('drivers/nric', 'public');
        }

        $driver->update($data);

        return redirect()->route('drivers.index')->with('success', 'Driver updated successfully!');
    }

    public function destroy(Driver $driver)
    {
        // Delete associated files
        if ($driver->profile_picture) Storage::disk('public')->delete($driver->profile_picture);
        if ($driver->license_photo) Storage::disk('public')->delete($driver->license_photo);
        if ($driver->nric_photo) Storage::disk('public')->delete($driver->nric_photo);

        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver deleted successfully!');
    }
}
