@extends('layouts.main')

@section('title', 'Company Settings')
@section('page-title', 'Company Settings')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Company Settings</h2>
        <p class="text-gray-600 mt-1">Manage your company settings and preferences</p>
    </div>

    <!-- Settings Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Company Profile -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Profile</h3>
            <div class="space-y-2">
                <a href="{{ route('settings.company.basic-information') }}" class="block text-blue-600 hover:text-blue-900">Basic Information</a>
                <a href="{{ route('settings.company.branding') }}" class="block text-blue-600 hover:text-blue-900">Branding</a>
                <a href="{{ route('settings.landing-page.edit') }}" class="block text-blue-600 hover:text-blue-900">Landing Page</a>
                <a href="{{ route('settings.auth-pages.edit') }}" class="block text-blue-600 hover:text-blue-900">Login & Registration Pages</a>
            </div>
        </div>

        <!-- Holidays -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Holidays</h3>
            <div class="space-y-2">
                <a href="{{ route('holidays.index') }}" class="block text-blue-600 hover:text-blue-900">Public Holidays</a>
                <a href="{{ route('holidays.create') }}" class="block text-blue-600 hover:text-blue-900">Add Holiday</a>
                <a href="{{ route('holidays.calendar') }}" class="block text-blue-600 hover:text-blue-900">Holiday Calendar</a>
            </div>
        </div>

        <!-- User Management -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">User Management</h3>
            <div class="space-y-2">
                <a href="{{ route('settings.users.index') }}" class="block text-blue-600 hover:text-blue-900">All Users</a>
                <a href="{{ route('settings.users.create') }}" class="block text-blue-600 hover:text-blue-900">Add User</a>
                <a href="{{ route('settings.users.roles-permissions') }}" class="block text-blue-600 hover:text-blue-900">Roles & Permissions</a>
            </div>
        </div>

        <!-- Subscription -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription</h3>
            <div class="space-y-2">
                <a href="{{ route('settings.subscription.current-plan') }}" class="block text-blue-600 hover:text-blue-900">Current Plan</a>
                <a href="{{ route('settings.subscription.billing-history') }}" class="block text-blue-600 hover:text-blue-900">Billing History</a>
                <a href="{{ route('settings.subscription.upgrade-plan') }}" class="block text-blue-600 hover:text-blue-900">Upgrade Plan</a>
            </div>
        </div>
    </div>
</div>
@endsection

