<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::orderBy('group')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SystemSetting::set($key, $value);
        }

        return back()->with('success', 'System settings updated successfully.');
    }
}
