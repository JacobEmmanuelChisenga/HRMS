@extends('layouts.main')

@section('title', 'Backups & Maintenance')
@section('page-title', 'Backups & Maintenance')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Backups & Maintenance</h2>
        <p class="text-gray-600 mt-1">Manage system backups and maintenance tasks</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Backup Management -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Backup Management</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Last Backup</p>
                    <p class="text-sm font-medium text-gray-900">Never</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Backup Frequency</p>
                    <p class="text-sm font-medium text-gray-900">Daily at 2:00 AM</p>
                </div>
                <button class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Backup Now
                </button>
            </div>
        </div>

        <!-- Maintenance Tasks -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Maintenance Tasks</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Cache Status</p>
                    <p class="text-sm font-medium text-gray-900">Active</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Database Size</p>
                    <p class="text-sm font-medium text-gray-900">2.5 MB</p>
                </div>
                <button class="w-full mt-4 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Clear Cache
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
