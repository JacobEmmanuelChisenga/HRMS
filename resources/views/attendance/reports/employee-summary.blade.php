@extends('layouts.main')

@section('title', 'Employee Attendance Summary')
@section('page-title', 'Employee Attendance Summary')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Employee Attendance Summary</h2>
            <p class="text-gray-600 mt-1">Select an employee to view detailed attendance</p>
        </div>
    </div>

    <!-- Employee Selection -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('attendance.reports.employee-summary') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee *</label>
                <select name="employee_id" id="employee_id" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->user->first_name }} {{ $employee->user->last_name }} ({{ $employee->employee_number }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from', date('Y-m-01')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to', date('Y-m-t')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    View Summary
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
