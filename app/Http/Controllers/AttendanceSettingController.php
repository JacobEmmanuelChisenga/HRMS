<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSetting;
use Carbon\Carbon;

class AttendanceSettingController extends Controller
{
    public function workHours()
    {
        $company = auth()->user()->company;
        $settings = AttendanceSetting::where('company_id', $company->id)->first();

        return view('attendance.settings.work-hours', compact('settings'));
    }

    public function updateWorkHours(Request $request)
    {
        $request->validate([
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
        ]);

        $company = auth()->user()->company;
        $settings = AttendanceSetting::firstOrNew(['company_id' => $company->id]);
        $settings->work_start_time = Carbon::parse($request->work_start_time)->toTimeString();
        $settings->work_end_time = Carbon::parse($request->work_end_time)->toTimeString();
        $settings->save();

        return redirect()->back()->with('success', 'Work hours updated successfully');
    }

    public function lateThresholds()
    {
        $company = auth()->user()->company;
        $settings = AttendanceSetting::where('company_id', $company->id)->first();

        return view('attendance.settings.late-thresholds', compact('settings'));
    }

    public function updateLateThresholds(Request $request)
    {
        $request->validate([
            'late_threshold_minutes' => 'required|integer|min:0|max:120',
            'grace_period_minutes' => 'required|integer|min:0|max:60',
        ]);

        $company = auth()->user()->company;
        $settings = AttendanceSetting::firstOrNew(['company_id' => $company->id]);
        $settings->late_threshold_minutes = $request->late_threshold_minutes;
        $settings->grace_period_minutes = $request->grace_period_minutes;
        $settings->save();

        return redirect()->back()->with('success', 'Late thresholds updated successfully');
    }

    public function geofencing()
    {
        $company = auth()->user()->company;
        $settings = AttendanceSetting::where('company_id', $company->id)->first();

        return view('attendance.settings.geofencing', compact('settings'));
    }

    public function updateGeofencing(Request $request)
    {
        $request->validate([
            'require_location' => 'boolean',
            'office_latitude' => 'nullable|numeric|between:-90,90',
            'office_longitude' => 'nullable|numeric|between:-180,180',
            'geofence_radius_meters' => 'nullable|integer|min:10|max:10000',
            'allow_mobile_clockin' => 'boolean',
        ]);

        $company = auth()->user()->company;
        $settings = AttendanceSetting::firstOrNew(['company_id' => $company->id]);
        $settings->require_location = $request->has('require_location');
        $settings->allow_mobile_clockin = $request->has('allow_mobile_clockin');
        
        if ($request->filled('office_latitude')) {
            $settings->office_latitude = $request->office_latitude;
        }
        
        if ($request->filled('office_longitude')) {
            $settings->office_longitude = $request->office_longitude;
        }
        
        if ($request->filled('geofence_radius_meters')) {
            $settings->geofence_radius_meters = $request->geofence_radius_meters;
        }
        
        $settings->save();

        return redirect()->back()->with('success', 'Geofencing settings updated successfully');
    }
}
