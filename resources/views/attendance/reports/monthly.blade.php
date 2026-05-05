@extends('layouts.main')

@section('title', 'Monthly Attendance Report')
@section('page-title', 'Monthly Attendance Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Monthly Attendance Report</h2>
            <p class="text-gray-600 mt-1">{{ Carbon\Carbon::parse($month)->format('F Y') }}</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('attendance.reports.monthly') }}" class="flex items-center space-x-3">
                <input type="month" name="month" value="{{ $month }}" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    View Report
                </button>
            </form>
            <a href="{{ route('attendance.reports.export', ['date_from' => Carbon\Carbon::parse($month)->startOfMonth()->toDateString(), 'date_to' => Carbon\Carbon::parse($month)->endOfMonth()->toDateString()]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Export
            </a>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">On Leave</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hours</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reportData as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $data['employee']->user->first_name }} {{ $data['employee']->user->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $data['employee']->employee_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data['total_days'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">{{ $data['present'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600 font-medium">{{ $data['late'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">{{ $data['absent'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">{{ $data['on_leave'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($data['total_hours'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No records found for this month</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
