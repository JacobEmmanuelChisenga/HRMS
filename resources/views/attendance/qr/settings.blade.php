@extends('layouts.main')

@section('title', 'QR Code Settings')
@section('page-title', 'QR Code Settings')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">QR Code Settings</h2>
            <p class="text-gray-600 mt-1">Configure QR code rotation and security</p>
        </div>
        <a href="{{ route('attendance.qr.display') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Display
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendance.qr.settings.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- QR Rotation Minutes -->
                <div>
                    <label for="qr_rotation_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                        QR Code Rotation (Minutes) *
                    </label>
                    <input type="number" name="qr_rotation_minutes" id="qr_rotation_minutes" 
                           value="{{ old('qr_rotation_minutes', $settings->qr_rotation_minutes ?? 5) }}" 
                           min="1" max="60" required
                           class="w-full md:w-64 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">
                        How often the QR code should rotate (1-60 minutes). Shorter intervals are more secure.
                    </p>
                    @error('qr_rotation_minutes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('attendance.qr.display') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
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
