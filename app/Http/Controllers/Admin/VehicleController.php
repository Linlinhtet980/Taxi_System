<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::query()->latest()->paginate(5);
        $totalVehicles = Vehicle::query()->count();
        $totalAvailable = Vehicle::query()->where('status', 'Available')->count();
        $totalMaintenance = Vehicle::query()->where('status', 'Maintenance')->count();
        
        return view('dashboardview.vehicle.index', compact('vehicles', 'totalVehicles', 'totalAvailable', 'totalMaintenance'));
    }

    public function create()
    {
        return view('dashboardview.vehicle.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_plate' => 'required|unique:vehicles|max:255',
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'vehicle_type' => 'required|max:255',
            'color' => 'nullable|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:Available,On Ride,Maintenance',
            'vehicle_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'front_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'back_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'left_side_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'right_side_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'interior_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('vehicle_photo')) {
            $validated['vehicle_photo'] = $request->file('vehicle_photo')->store('vehicle_photos', 'public');
        }

        $photoFields = ['front_photo', 'back_photo', 'left_side_photo', 'right_side_photo', 'interior_photo'];
        foreach ($photoFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('vehicles/inspections', 'public');
            }
        }

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle registered successfully.');
    }


    public function show(Vehicle $vehicle)
    {
        return view('dashboardview.vehicle.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('dashboardview.vehicle.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'license_plate' => 'required|max:255|unique:vehicles,license_plate,' . $vehicle->id,
            'brand' => 'required|max:255',
            'model' => 'required|max:255',
            'vehicle_type' => 'required|max:255',
            'color' => 'nullable|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'status' => 'required|in:Available,On Ride,Maintenance',
            'vehicle_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'front_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'back_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'left_side_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'right_side_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'interior_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('vehicle_photo')) {
            if ($vehicle->vehicle_photo) {
                Storage::disk('public')->delete($vehicle->vehicle_photo);
            }
            $validated['vehicle_photo'] = $request->file('vehicle_photo')->store('vehicle_photos', 'public');
        }

        $photoFields = ['front_photo', 'back_photo', 'left_side_photo', 'right_side_photo', 'interior_photo'];
        foreach ($photoFields as $field) {
            if ($request->hasFile($field)) {
                if ($vehicle->$field) {
                    Storage::disk('public')->delete($vehicle->$field);
                }
                $validated[$field] = $request->file($field)->store('vehicles/inspections', 'public');
            }
        }

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')->with('success', 'Vehicle information updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->vehicle_photo) {
            Storage::disk('public')->delete($vehicle->vehicle_photo);
        }
        
        $photoFields = ['front_photo', 'back_photo', 'left_side_photo', 'right_side_photo', 'interior_photo'];
        foreach ($photoFields as $field) {
            if ($vehicle->$field) {
                Storage::disk('public')->delete($vehicle->$field);
            }
        }

        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle removed from fleet.');
    }
}
