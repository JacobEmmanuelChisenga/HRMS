@extends('layouts.main')

@section('title', 'Detailed Leave Report')
@section('page-title', 'Detailed Leave Report')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Detailed Leave Report</h2>
            <p class="text-gray-600 mt-1">Detailed view of leave requests for {{ $year }}</p>
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
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    <!-- Detailed Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leaves as $leave)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $leave->employee->user->first_name }} {{ $leave->employee->user->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $leave->employee->employee_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->leaveType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->start_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->end_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->days_requested }} days</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$leave->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->approvedBy->first_name ?? 'N/A' }} {{ $leave->approvedBy->last_name ?? '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No leave requests found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">{{ $leaves->links() }}</div>
    </div>
</div>
@endsection
