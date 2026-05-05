@extends('layouts.main')

@section('title', 'System Logs')
@section('page-title', 'System Logs')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">System Logs</h2>
        <p class="text-gray-600 mt-1">View and monitor system activity logs</p>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex space-x-4">
                    <select class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                        <option>All Logs</option>
                        <option>Error Logs</option>
                        <option>Access Logs</option>
                        <option>Activity Logs</option>
                    </select>
                    <input type="date" class="border border-gray-300 rounded-lg px-4 py-2 text-sm" placeholder="Filter by date">
                </div>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    Export Logs
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <!-- Log Entry Example -->
                <div class="border-l-4 border-blue-500 pl-4 py-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">User login successful</p>
                            <p class="text-xs text-gray-500 mt-1">user@example.com - 2026-01-21 10:30:45</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded">Info</span>
                    </div>
                </div>

                <div class="border-l-4 border-green-500 pl-4 py-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Employee created</p>
                            <p class="text-xs text-gray-500 mt-1">John Doe - 2026-01-21 09:15:22</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded">Success</span>
                    </div>
                </div>

                <div class="border-l-4 border-yellow-500 pl-4 py-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Failed login attempt</p>
                            <p class="text-xs text-gray-500 mt-1">unknown@example.com - 2026-01-21 08:45:10</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded">Warning</span>
                    </div>
                </div>

                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Database connection error</p>
                            <p class="text-xs text-gray-500 mt-1">System - 2026-01-21 07:20:33</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded">Error</span>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-600">Showing 1-10 of 150 entries</p>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Previous</button>
                    <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
