@extends('layouts.main')

@section('title', 'Leave Type Details')
@section('page-title', 'Leave Type Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $leaveType->name }}</h2>
            <p class="text-gray-600 mt-1">Leave type details and usage statistics</p>
        </div>
        <div class="flex space-x-3">
            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
            <a href="{{ route('leave-types.edit', $leaveType->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
            <a href="{{ route('leave-types.policies', $leaveType->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Configure Policies</a>
            @endif
            @if(auth()->user()->hasRole('company_admin'))
            <form action="{{ route('leave-types.destroy', $leaveType->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave type? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Delete</button>
            </form>
            @endif
            <a href="{{ route('leave-types.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave Type Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Name</label>
                        <p class="text-gray-900 font-medium">{{ $leaveType->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Days Allowed</label>
                        <p class="text-gray-900 font-medium">{{ $leaveType->days_allowed }} days</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $leaveType->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($leaveType->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Is Paid</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $leaveType->is_paid ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $leaveType->is_paid ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($leaveType->description)
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1">{{ $leaveType->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Policies</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Requires Approval</label>
                        <p class="text-gray-900 font-medium">{{ $leaveType->requires_approval ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Carries Forward</label>
                        <p class="text-gray-900 font-medium">{{ $leaveType->carries_forward ? 'Yes' : 'No' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Requires Document</label>
                        <p class="text-gray-900 font-medium">{{ $leaveType->requires_document ? 'Yes' : 'No' }}</p>
                    </div>
                    @if($leaveType->carries_forward && $leaveType->max_carry_forward_days)
                    <div>
                        <label class="text-sm text-gray-500">Max Carry Forward Days</label>
                        <p class="text-gray-900 font-medium">{{ $leaveType->max_carry_forward_days }} days</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $leaveType->leaveRequests->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active Balances</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $leaveType->leaveBalances->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
