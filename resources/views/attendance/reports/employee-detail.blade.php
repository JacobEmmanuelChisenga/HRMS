@extends('layouts.main')

@section('title', 'Employee Attendance Detail')
@section('page-title', 'Employee Attendance Detail')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h2>
            <p class="text-gray-600 mt-1">Attendance Summary: {{ Carbon\Carbon::parse($dateFrom)->format('M d') }} - {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('attendance.reports.employee-summary') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Total Days</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_days'] }}</p>
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
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Avg Hours</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['average_hours'] }}</p>
        </div>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date->format('M d, Y') }}</td>
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
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
