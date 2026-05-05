@extends('layouts.main')

@section('title', 'Audit & Compliance')
@section('page-title', 'Audit & Compliance')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Audit & Compliance</h2>
        <p class="text-gray-600 mt-1">Comprehensive audit trails and compliance tracking</p>
    </div>

    <!-- Audit Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Audit Trails -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Audit Trails</h3>
            <div class="space-y-2">
                <a href="{{ route('audit.activities.index') }}" class="block text-blue-600 hover:text-blue-900">All Activities</a>
                <a href="{{ route('audit.activities.user-actions') }}" class="block text-blue-600 hover:text-blue-900">User Actions</a>
                <a href="{{ route('audit.activities.data-changes') }}" class="block text-blue-600 hover:text-blue-900">Data Changes</a>
            </div>
        </div>

        <!-- Document Access Logs -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Access</h3>
            <div class="space-y-2">
                <a href="{{ route('audit.documents.access-logs') }}" class="block text-blue-600 hover:text-blue-900">Document Access Logs</a>
            </div>
        </div>

        <!-- Attendance Modifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance</h3>
            <div class="space-y-2">
                <a href="{{ route('audit.attendance.modifications') }}" class="block text-blue-600 hover:text-blue-900">Attendance Modifications</a>
            </div>
        </div>

        <!-- Compliance Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Compliance</h3>
            <div class="space-y-2">
                <a href="{{ route('audit.compliance.reports') }}" class="block text-blue-600 hover:text-blue-900">Compliance Reports</a>
            </div>
        </div>
    </div>
</div>
@endsection
