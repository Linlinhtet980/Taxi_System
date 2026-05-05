<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::query()->latest()->paginate(5);
        $totalCustomers = Customer::query()->count('*');
        $activeCustomers = Customer::query()->where('status', 'active')->count('*');
        
        return view('dashboardview.customer.index', compact('customers', 'totalCustomers', 'activeCustomers'));
    }

    public function create()
    {
        return view('dashboardview.customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'required|unique:customers',
            'password' => 'nullable|min:6',
            'address' => 'nullable',
            'profile_picture' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_profile_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/customers'), $filename);
            $validated['profile_picture'] = 'uploads/customers/' . $filename;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer registered successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('dashboardview.customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        $statusText = $customer->status == 'active' ? 'activated' : 'blocked';
        return redirect()->route('customers.index')->with('success', "Passenger account has been successfully {$statusText}.");
    }

    public function destroy(Customer $customer)
    {
        if ($customer->profile_picture) {
            Storage::disk('public')->delete($customer->profile_picture);
        }
        Customer::query()->where('id', $customer->id)->delete();
        return redirect()->route('customers.index')->with('success', 'Customer removed.');
    }
}
