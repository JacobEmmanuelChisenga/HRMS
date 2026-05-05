@extends('layouts.main')

@section('title', 'Geofencing Settings')
@section('page-title', 'Geofencing Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Geofencing Settings</h2>
            <p class="text-gray-600 mt-1">Configure location-based attendance requirements</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendance.settings.geofencing.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Require Location -->
                <div class="flex items-center">
                    <input type="checkbox" name="require_location" id="require_location" value="1"
                           {{ old('require_location', $settings && $settings->require_location ? 'checked' : '') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="require_location" class="ml-2 block text-sm text-gray-900">
                        Require Location for Clock In/Out
                    </label>
                </div>
                <p class="text-sm text-gray-500 ml-6">Employees must be within the geofence area to clock in/out</p>

                <!-- Allow Mobile Clock In -->
                <div class="flex items-center">
                    <input type="checkbox" name="allow_mobile_clockin" id="allow_mobile_clockin" value="1"
                           {{ old('allow_mobile_clockin', $settings && $settings->allow_mobile_clockin ? 'checked' : '') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="allow_mobile_clockin" class="ml-2 block text-sm text-gray-900">
                        Allow Mobile Clock In/Out
                    </label>
                </div>
                <p class="text-sm text-gray-500 ml-6">Allow employees to clock in/out using mobile devices</p>

                <!-- Office Location -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-200">
                    <div>
                        <label for="office_latitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Office Latitude
                        </label>
                        <input type="number" step="any" name="office_latitude" id="office_latitude" 
                               value="{{ old('office_latitude', $settings->office_latitude ?? '') }}" 
                               min="-90" max="90"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Office location latitude (-90 to 90)</p>
                        @error('office_latitude')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="office_longitude" class="block text-sm font-medium text-gray-700 mb-2">
                            Office Longitude
                        </label>
                        <input type="number" step="any" name="office_longitude" id="office_longitude" 
                               value="{{ old('office_longitude', $settings->office_longitude ?? '') }}" 
                               min="-180" max="180"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Office location longitude (-180 to 180)</p>
                        @error('office_longitude')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="geofence_radius_meters" class="block text-sm font-medium text-gray-700 mb-2">
                            Geofence Radius (Meters)
                        </label>
                        <input type="number" name="geofence_radius_meters" id="geofence_radius_meters" 
                               value="{{ old('geofence_radius_meters', $settings->geofence_radius_meters ?? '100') }}" 
                               min="10" max="10000"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Allowed radius from office (10-10000 meters)</p>
                        @error('geofence_radius_meters')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('attendance.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
