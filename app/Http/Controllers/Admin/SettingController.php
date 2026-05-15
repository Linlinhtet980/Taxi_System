<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Auth\User|null $user */
        $user = request()->user();
        if ($user && !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized. Access restricted to Super Administrators only.');
        }

        $settings = Setting::query()->get()->groupBy('group');
        
        // Ensure 'general' is always first if it exists
        if ($settings->has('general')) {
            $general = $settings->pull('general');
            $settings = collect(['general' => $general])->merge($settings);
        }
        
        return view('dashboardview.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Auth\User|null $user */
        $user = $request->user();
        if ($user && !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized. Access restricted to Super Administrators only.');
        }

        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::query()->where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
