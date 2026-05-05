@extends('layouts.main')

@section('title', 'Mark Attendance')
@section('page-title', 'Mark Attendance')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Mark Attendance</h2>
            <p class="text-gray-600 mt-1">Manually mark attendance for employees</p>
        </div>
        <a href="{{ route('attendance.today') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Today's Attendance
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('attendance.mark') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Employee -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                    <select name="employee_id" id="employee_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user->first_name }} {{ $employee->user->last_name }} ({{ $employee->employee_number }})
                        </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                    <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Clock In -->
                <div>
                    <label for="clock_in" class="block text-sm font-medium text-gray-700 mb-2">Clock In</label>
                    <input type="time" name="clock_in" id="clock_in" value="{{ old('clock_in') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('clock_in')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Clock Out -->
                <div>
                    <label for="clock_out" class="block text-sm font-medium text-gray-700 mb-2">Clock Out</label>
                    <input type="time" name="clock_out" id="clock_out" value="{{ old('clock_out') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('clock_out')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="late" {{ old('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="half_day" {{ old('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                        <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('attendance.today') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
