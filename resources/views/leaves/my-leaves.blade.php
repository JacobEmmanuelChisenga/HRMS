@extends('layouts.main')

@section('title', 'My Leaves')
@section('page-title', 'My Leaves')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Leave Requests</h2>
            <p class="text-gray-600 mt-1">View and manage your leave requests</p>
        </div>
        <a href="{{ route('leaves.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Request Leave</span>
        </a>
    </div>

    <!-- Leave Requests -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($leaves as $leave)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $leave->leaveType->name }}</h3>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'approved' => 'bg-green-100 text-green-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'cancelled' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$leave->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($leave->status) }}
                        </span>
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
                            <label class="text-xs text-gray-500">Days</label>
                            <p class="text-sm font-medium text-gray-900">{{ $leave->days_requested }} days</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">Requested</label>
                            <p class="text-sm font-medium text-gray-900">{{ $leave->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @if($leave->reason)
                    <div class="mt-4">
                        <label class="text-xs text-gray-500">Reason</label>
                        <p class="text-sm text-gray-700 mt-1">{{ $leave->reason }}</p>
                    </div>
                    @endif
                    @if($leave->approver_comments)
                    <div class="mt-4">
                        <label class="text-xs text-gray-500">Comments</label>
                        <p class="text-sm text-gray-700 mt-1">{{ $leave->approver_comments }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-500 mb-4">No leave requests yet</p>
            <a href="{{ route('leaves.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Request Leave</a>
        </div>
        @endforelse
    </div>

    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 rounded-lg">
        {{ $leaves->links() }}
    </div>
</div>
@endsection
