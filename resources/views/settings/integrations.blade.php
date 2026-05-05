@extends('layouts.main')

@section('title', 'System Integrations')
@section('page-title', 'System Integrations')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">System Integrations</h2>
        <p class="text-gray-600 mt-1">Manage system integrations and third-party connections</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="space-y-4">
            <!-- Email Integration -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Email Service</h3>
                        <p class="text-sm text-gray-600 mt-1">Configure SMTP settings for email notifications</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">Active</span>
                </div>
            </div>

            <!-- SMS Integration -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">SMS Service</h3>
                        <p class="text-sm text-gray-600 mt-1">Configure SMS gateway for notifications</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Not Configured</span>
                </div>
            </div>

            <!-- Biometric Integration -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Biometric Devices</h3>
                        <p class="text-sm text-gray-600 mt-1">Connect biometric attendance devices</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Not Configured</span>
                </div>
            </div>

            <!-- API Integration -->
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">API Access</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage API keys and webhooks</p>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Not Configured</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
