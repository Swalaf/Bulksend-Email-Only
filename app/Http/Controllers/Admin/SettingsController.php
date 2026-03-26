<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'support_email' => 'required|email',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'vendor_commission_rate' => 'required|numeric|min:0|max:100',
            'maintenance_mode' => 'boolean',
            'registration_enabled' => 'boolean',
            'email_verification_required' => 'boolean',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('settings');

        return back()->with('success', 'Settings updated');
    }

    public function appearance()
    {
        $settings = Setting::where('key', 'like', 'theme_%')
            ->pluck('value', 'key')
            ->toArray();
        
        return view('admin.settings.appearance', compact('settings'));
    }

    public function updateAppearance(Request $request)
    {
        $validated = $request->validate([
            'theme_primary_color' => 'required|string',
            'theme_secondary_color' => 'required|string',
            'theme_logo' => 'nullable|image|max:2048',
            'theme_favicon' => 'nullable|image|max:512',
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null && !is_object($value)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return back()->with('success', 'Appearance updated');
    }

    public function email()
    {
        $settings = Setting::where('key', 'like', 'mail_%')
            ->pluck('value', 'key')
            ->toArray();
        
        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Email settings updated');
    }

    public function logs()
    {
        $logFiles = glob(storage_path('logs/*.log'));
        $logs = [];
        
        foreach ($logFiles as $file) {
            $logs[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'modified' => filemtime($file),
            ];
        }

        rsort($logs);

        return view('admin.settings.logs', compact('logs'));
    }

    public function viewLog($filename)
    {
        $path = storage_path('logs/' . $filename);
        
        if (!file_exists($path)) {
            abort(404);
        }

        $content = file_get_contents($path);
        $lines = explode("\n", $content);
        $lines = array_slice($lines, -500);

        return view('admin.settings.log-viewer', compact('filename', 'lines'));
    }

    public function clearLog($filename)
    {
        $path = storage_path('logs/' . $filename);
        
        if (file_exists($path)) {
            file_put_contents($path, '');
        }

        return back()->with('success', 'Log cleared');
    }
}
