@extends('layouts.main')

@section('title', 'Leave Summary Report')
@section('page-title', 'Leave Summary Report')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Leave Summary Report</h2>
            <p class="text-gray-600 mt-1">Overview of leave requests for {{ $year }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reports.leaves.export', ['year' => $year, 'format' => 'excel']) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Export Excel</a>
            <a href="{{ route('reports.leaves.export', ['year' => $year, 'format' => 'pdf']) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Export PDF</a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <input type="number" name="year" value="{{ $year }}" min="2020" max="2100" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                <select name="leave_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $leaveType)
                    <option value="{{ $leaveType->id }}" {{ $leaveTypeId == $leaveType->id ? 'selected' : '' }}>{{ $leaveType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Requests</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $summary['total_requests'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $summary['approved'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $summary['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm text-gray-500">Total Days</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $summary['total_days'] }}</p>
        </div>
    </div>

    <!-- By Leave Type -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">By Leave Type</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requests</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Days</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($summary['by_type'] as $type)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $type['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $type['count'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $type['days'] }} days</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No data available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
