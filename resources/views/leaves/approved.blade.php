@extends('layouts.main')

@section('title', 'Approved Leaves')
@section('page-title', 'Approved Leaves')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Approved Leaves</h2>
        <p class="text-gray-600 mt-1">View all approved leave requests</p>
    </div>

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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved At</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->approvedBy->first_name ?? 'N/A' }} {{ $leave->approvedBy->last_name ?? '' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leave->approved_at ? $leave->approved_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No approved leaves found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">{{ $leaves->links() }}</div>
    </div>
</div>
@endsection
