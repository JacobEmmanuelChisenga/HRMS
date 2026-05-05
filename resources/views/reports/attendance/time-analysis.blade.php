@extends('layouts.main')

@section('title', 'Time Analysis')
@section('page-title', 'Time Analysis')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Time Analysis</h2>
            <p class="text-gray-600 mt-1">Clock in/out patterns and hours analysis</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('reports.attendance.time-analysis') }}" class="flex items-center space-x-3">
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

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Average Clock In</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $avgClockIn ? $avgClockIn->format('h:i A') : 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Average Clock Out</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $avgClockOut ? $avgClockOut->format('h:i A') : 'N/A' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-600">Average Hours/Day</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $averageHours }}</p>
        </div>
    </div>

    <!-- Hours Distribution -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Hours Distribution</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($hoursDistribution as $range => $count)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $range }} hours</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
