@extends('layouts.main')

@section('title', 'Edit Attendance Record')
@section('page-title', 'Edit Attendance Record')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Attendance Record</h2>
            <p class="text-gray-600 mt-1">Update attendance information</p>
        </div>
        <a href="{{ route('attendance.records.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Records
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6 pb-4 border-b border-gray-200">
            <p class="text-sm text-gray-600"><strong>Employee:</strong> {{ $record->employee->user->first_name }} {{ $record->employee->user->last_name }}</p>
            <p class="text-sm text-gray-600"><strong>Date:</strong> {{ $record->date->format('M d, Y') }}</p>
        </div>

        <form action="{{ route('attendance.records.update', $record->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Clock In -->
                <div>
                    <label for="clock_in" class="block text-sm font-medium text-gray-700 mb-2">Clock In</label>
                    <input type="time" name="clock_in" id="clock_in" 
                           value="{{ old('clock_in', $record->clock_in ? Carbon\Carbon::parse($record->clock_in)->format('H:i') : '') }}" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('clock_in')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Clock Out -->
                <div>
                    <label for="clock_out" class="block text-sm font-medium text-gray-700 mb-2">Clock Out</label>
                    <input type="time" name="clock_out" id="clock_out" 
                           value="{{ old('clock_out', $record->clock_out ? Carbon\Carbon::parse($record->clock_out)->format('H:i') : '') }}" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('clock_out')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="present" {{ old('status', $record->status) == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="late" {{ old('status', $record->status) == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="absent" {{ old('status', $record->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="half_day" {{ old('status', $record->status) == 'half_day' ? 'selected' : '' }}>Half Day</option>
                        <option value="on_leave" {{ old('status', $record->status) == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $record->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('attendance.records.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
