@extends('layouts.main')

@section('title', 'Department Comparison')
@section('page-title', 'Department Comparison')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Department Comparison</h2>
            <p class="text-gray-600 mt-1">Compare attendance across departments</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('reports.attendance.department-comparison') }}" class="flex items-center space-x-3">
                <input type="month" name="month" value="{{ $month }}" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </form>
            <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employees</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Hours</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($comparison as $comp)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $comp['department'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $comp['total_employees'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">{{ $comp['present'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $comp['late'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $comp['absent'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $comp['attendance_rate'] }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $comp['average_hours'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
