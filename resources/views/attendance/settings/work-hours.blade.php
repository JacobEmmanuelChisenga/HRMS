@extends('layouts.main')

@section('title', 'Work Hours Settings')
@section('page-title', 'Work Hours Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Work Hours Settings</h2>
            <p class="text-gray-600 mt-1">Configure standard work hours for attendance</p>
        </div>
        <a href="{{ route('attendance.dashboard') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Dashboard
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendance.settings.work-hours.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Work Start Time -->
                    <div>
                        <label for="work_start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Work Start Time *
                        </label>
                        <input type="time" name="work_start_time" id="work_start_time" 
                               value="{{ old('work_start_time', $settings && $settings->work_start_time ? Carbon\Carbon::parse($settings->work_start_time)->format('H:i') : '08:00') }}" 
                               required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Standard start time for work day</p>
                        @error('work_start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Work End Time -->
                    <div>
                        <label for="work_end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Work End Time *
                        </label>
                        <input type="time" name="work_end_time" id="work_end_time" 
                               value="{{ old('work_end_time', $settings && $settings->work_end_time ? Carbon\Carbon::parse($settings->work_end_time)->format('H:i') : '17:00') }}" 
                               required
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Standard end time for work day</p>
                        @error('work_end_time')
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
