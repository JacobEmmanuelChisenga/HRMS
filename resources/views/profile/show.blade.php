@extends('layouts.main')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Profile Overview</h2>
            <p class="text-gray-600 mt-1">View your account details and employment info</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Edit Profile
            </a>
            <a href="{{ route('profile.change-password') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200">
                Change Password
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Account Info -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Account</h3>
            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Name</span>
                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-900">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Role</span>
                    <span class="font-medium text-gray-900">{{ $user->role->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Company</span>
                    <span class="font-medium text-gray-900">{{ $user->company->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Employment Info -->
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Employment</h3>
            <div class="space-y-3 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span class="text-gray-500">Employee Number</span>
                    <span class="font-medium text-gray-900">{{ $employeeProfile->employee_number ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Department</span>
                    <span class="font-medium text-gray-900">{{ $employeeProfile->department->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Position</span>
                    <span class="font-medium text-gray-900">{{ $employeeProfile->position->title ?? ($user->role->name ?? 'N/A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Hire Date</span>
                    <span class="font-medium text-gray-900">
                        @if(isset($employeeProfile->hire_date))
                            {{ \Carbon\Carbon::parse($employeeProfile->hire_date)->format('M d, Y') }}
                        @else
                            N/A
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="font-medium text-gray-900">{{ ucfirst($employeeProfile->employment_status ?? 'N/A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
