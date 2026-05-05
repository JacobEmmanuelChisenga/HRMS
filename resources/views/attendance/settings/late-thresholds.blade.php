@extends('layouts.main')

@section('title', 'Late Thresholds Settings')
@section('page-title', 'Late Thresholds Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Late Thresholds Settings</h2>
            <p class="text-gray-600 mt-1">Configure late arrival thresholds and grace periods</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendance.settings.late-thresholds.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Late Threshold Minutes -->
                    <div>
                        <label for="late_threshold_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                            Late Threshold (Minutes) *
                        </label>
                        <input type="number" name="late_threshold_minutes" id="late_threshold_minutes" 
                               value="{{ old('late_threshold_minutes', $settings->late_threshold_minutes ?? 15) }}" 
                               min="0" max="120" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">
                            Minutes after work start time before marking as late (0-120 minutes)
                        </p>
                        @error('late_threshold_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grace Period Minutes -->
                    <div>
                        <label for="grace_period_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                            Grace Period (Minutes) *
                        </label>
                        <input type="number" name="grace_period_minutes" id="grace_period_minutes" 
                               value="{{ old('grace_period_minutes', $settings->grace_period_minutes ?? 5) }}" 
                               min="0" max="60" required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">
                            Grace period before late threshold (0-60 minutes)
                        </p>
                        @error('grace_period_minutes')
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
