<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::getAllGrouped();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Convert boolean checkboxes
                if ($setting->type === 'boolean') {
                    $value = $request->has("settings.{$key}") ? '1' : '0';
                }

                $setting->update(['value' => $value]);

                // Log activity
                ActivityLog::log(
                    'updated',
                    'Setting',
                    $setting->id,
                    "Updated setting: {$setting->key}",
                    ['old' => $setting->getOriginal('value'), 'new' => $value]
                );
            }
        }

        // Clear cache
        Setting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    public function clearCache()
    {
        Setting::clearCache();
        
        ActivityLog::log('cleared', 'Cache', null, 'Cleared settings cache');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Cache cleared successfully!');
    }
}
