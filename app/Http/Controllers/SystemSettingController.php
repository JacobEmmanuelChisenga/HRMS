<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SystemSettingController extends Controller
{

    public function general()
    {
        return view('super-admin.settings.general');
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string|max:100',
        ]);

        // Update config or database settings
        // This is a placeholder - implement based on your settings storage method
        
        return redirect()->route('system-settings.general')
            ->with('success', 'General settings updated successfully.');
    }

    public function email()
    {
        return view('super-admin.settings.email');
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:20',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        // Update email configuration
        // This is a placeholder - implement based on your settings storage method
        
        return redirect()->route('system-settings.email')
            ->with('success', 'Email settings updated successfully.');
    }

    public function security()
    {
        return view('super-admin.settings.security');
    }

    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => 'required|integer|min:6|max:20',
            'password_require_uppercase' => 'boolean',
            'password_require_lowercase' => 'boolean',
            'password_require_numbers' => 'boolean',
            'password_require_symbols' => 'boolean',
            'session_lifetime' => 'required|integer|min:1|max:1440',
            'max_login_attempts' => 'required|integer|min:3|max:10',
        ]);

        // Update security settings
        // This is a placeholder - implement based on your settings storage method
        
        return redirect()->route('system-settings.security')
            ->with('success', 'Security settings updated successfully.');
    }

    public function features()
    {
        $features = [
            'leave_management' => Cache::get('feature.leave_management', true),
            'attendance_tracking' => Cache::get('feature.attendance_tracking', true),
            'document_management' => Cache::get('feature.document_management', true),
            'qr_code_attendance' => Cache::get('feature.qr_code_attendance', true),
            'reports' => Cache::get('feature.reports', true),
        ];
        
        return view('super-admin.settings.features', compact('features'));
    }

    public function updateFeatures(Request $request)
    {
        $features = [
            'leave_management',
            'attendance_tracking',
            'document_management',
            'qr_code_attendance',
            'reports',
        ];

        foreach ($features as $feature) {
            Cache::put("feature.{$feature}", $request->has($feature));
        }

        return redirect()->route('system-settings.features')
            ->with('success', 'Feature toggles updated successfully.');
    }

    public function maintenance()
    {
        $isMaintenanceMode = app()->isDownForMaintenance();
        
        return view('super-admin.settings.maintenance', compact('isMaintenanceMode'));
    }

    public function toggleMaintenance(Request $request)
    {
        if (app()->isDownForMaintenance()) {
            Artisan::call('up');
            $message = 'Maintenance mode disabled.';
        } else {
            Artisan::call('down', [
                '--secret' => $request->input('secret', 'maintenance-secret'),
            ]);
            $message = 'Maintenance mode enabled.';
        }

        return redirect()->route('system-settings.maintenance')
            ->with('success', $message);
    }
}
