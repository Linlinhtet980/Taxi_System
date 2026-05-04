<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Core\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->get()->groupBy('group');
        return view('dashboardview.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::query()->where('key', $key)->update(['value' => $value]);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
