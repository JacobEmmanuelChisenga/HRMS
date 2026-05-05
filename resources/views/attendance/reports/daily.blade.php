@extends('layouts.main')

@section('title', 'Daily Attendance Report')
@section('page-title', 'Daily Attendance Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Daily Attendance Report</h2>
            <p class="text-gray-600 mt-1">{{ Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('attendance.reports.daily') }}" class="flex items-center space-x-3">
                <input type="date" name="date" value="{{ $date }}" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    View Report
                </button>
            </form>
            <a href="{{ route('attendance.reports.export', ['date_from' => $date, 'date_to' => $date]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Export
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Present</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['present'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Late</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['late'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Absent</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['absent'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">On Leave</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['on_leave'] }}</p>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $record->employee->user->first_name }} {{ $record->employee->user->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $record->employee->employee_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->employee->department->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->employee->position->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->clock_in ? Carbon\Carbon::parse($record->clock_in)->format('h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->clock_out ? Carbon\Carbon::parse($record->clock_out)->format('h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->hours_worked ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'present' => 'bg-green-100 text-green-800',
                                    'late' => 'bg-yellow-100 text-yellow-800',
                                    'absent' => 'bg-red-100 text-red-800',
                                    'half_day' => 'bg-orange-100 text-orange-800',
                                    'on_leave' => 'bg-blue-100 text-blue-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$record->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No records found for this date</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
