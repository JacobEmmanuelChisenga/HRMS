@extends('layouts.main')

@section('title', 'Current Plan')
@section('page-title', 'Current Plan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Current Subscription Plan</h2>
            <p class="text-gray-600 mt-1">View your current subscription details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('settings.subscription.upgrade-plan') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Upgrade Plan
            </a>
            <a href="{{ route('settings.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Settings
            </a>
        </div>
    </div>

    <!-- Current Plan -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900">{{ ucfirst($company->subscription_plan ?? 'No Plan') }}</h3>
                <p class="text-gray-600 mt-1">Current subscription plan</p>
            </div>
            @if($company->subscription_expires_at)
            <div class="text-right">
                <p class="text-sm text-gray-600">Expires on</p>
                <p class="text-lg font-semibold text-gray-900">{{ Carbon\Carbon::parse($company->subscription_expires_at)->format('M d, Y') }}</p>
            </div>
            @endif
        </div>

        <!-- Plan Features -->
        <div class="border-t border-gray-200 pt-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-4">Plan Features</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Unlimited Employees</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Attendance Tracking</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Leave Management</span>
                </div>
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-gray-700">Document Management</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
