@extends('layouts.main')

@section('title', 'Pending Leave Approvals')
@section('page-title', 'Pending Leave Approvals')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Pending Leave Approvals</h2>
        <p class="text-gray-600 mt-1">Review and approve leave requests from your team</p>
    </div>

    <!-- Pending Leaves -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($leaves as $leave)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($leave->employee->user->first_name, 0, 1) . substr($leave->employee->user->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $leave->employee->user->first_name }} {{ $leave->employee->user->last_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $leave->employee->employee_number }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $leave->leaveType->name }}</span>
                            <span class="text-sm text-gray-600">{{ $leave->days_requested }} days</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div>
                                <label class="text-xs text-gray-500">Start Date</label>
                                <p class="text-sm font-medium text-gray-900">{{ $leave->start_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">End Date</label>
                                <p class="text-sm font-medium text-gray-900">{{ $leave->end_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Requested</label>
                                <p class="text-sm font-medium text-gray-900">{{ $leave->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Department</label>
                                <p class="text-sm font-medium text-gray-900">{{ $leave->employee->department->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($leave->reason)
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <label class="text-xs text-gray-500">Reason</label>
                            <p class="text-sm text-gray-700 mt-1">{{ $leave->reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="ml-6 flex flex-col space-y-2">
                    <form action="{{ route('leaves.approve', $leave->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Approve</button>
                    </form>
                    <form action="{{ route('leaves.reject', $leave->id) }}" method="POST" class="inline" x-data="{ showComments: false }">
                        @csrf
                        <button type="button" @click="showComments = !showComments" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Reject</button>
                        <div x-show="showComments" class="mt-2" style="display: none;">
                            <textarea name="comments" rows="3" placeholder="Rejection reason..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
                            <button type="submit" class="mt-2 w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm">Confirm Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-500">No pending leave requests</p>
        </div>
        @endforelse
    </div>

    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 rounded-lg">
        {{ $leaves->links() }}
    </div>
</div>
@endsection
