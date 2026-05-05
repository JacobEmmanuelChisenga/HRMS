@extends('layouts.main')

@section('title', 'Leave Trends')
@section('page-title', 'Leave Trends')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Leave Trends</h2>
            <p class="text-gray-600 mt-1">Monthly leave request trends</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('reports.leaves.trends') }}" class="flex items-center space-x-3">
                <select name="months" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="6" {{ $months == 6 ? 'selected' : '' }}>Last 6 months</option>
                    <option value="12" {{ $months == 12 ? 'selected' : '' }}>Last 12 months</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </form>
            <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Trends Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Requests</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pending</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Days</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($trends as $trend)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $trend['month'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trend['total_requests'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">{{ $trend['approved'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-600">{{ $trend['pending'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">{{ $trend['rejected'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trend['total_days'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
