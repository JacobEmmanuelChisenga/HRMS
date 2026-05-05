@extends('layouts.main')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Reports & Analytics</h2>
        <p class="text-gray-600 mt-1">Generate and view comprehensive reports</p>
    </div>

    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Employee Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Employee Reports</h3>
            <div class="space-y-2">
                <a href="{{ route('reports.employees.list') }}" class="block text-blue-600 hover:text-blue-900">Employee List</a>
                <a href="{{ route('reports.employees.headcount') }}" class="block text-blue-600 hover:text-blue-900">Headcount Analysis</a>
                <a href="{{ route('reports.employees.demographics') }}" class="block text-blue-600 hover:text-blue-900">Demographics</a>
            </div>
        </div>

        <!-- Attendance Analytics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Analytics</h3>
            <div class="space-y-2">
                <a href="{{ route('reports.attendance.trends') }}" class="block text-blue-600 hover:text-blue-900">Attendance Trends</a>
                <a href="{{ route('reports.attendance.department-comparison') }}" class="block text-blue-600 hover:text-blue-900">Department Comparison</a>
                <a href="{{ route('reports.attendance.time-analysis') }}" class="block text-blue-600 hover:text-blue-900">Time Analysis</a>
            </div>
        </div>

        <!-- Leave Analytics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave Analytics</h3>
            <div class="space-y-2">
                <a href="{{ route('reports.leaves.utilization') }}" class="block text-blue-600 hover:text-blue-900">Leave Utilization</a>
                <a href="{{ route('reports.leaves.trends') }}" class="block text-blue-600 hover:text-blue-900">Leave Trends</a>
                <a href="{{ route('reports.leaves.cost-analysis') }}" class="block text-blue-600 hover:text-blue-900">Cost Analysis</a>
            </div>
        </div>

        <!-- Document Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Reports</h3>
            <div class="space-y-2">
                <a href="{{ route('reports.documents.inventory') }}" class="block text-blue-600 hover:text-blue-900">Document Inventory</a>
                <a href="{{ route('reports.documents.compliance') }}" class="block text-blue-600 hover:text-blue-900">Compliance Status</a>
            </div>
        </div>

        <!-- Custom Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Custom Reports</h3>
            <div class="space-y-2">
                <a href="{{ route('reports.custom.builder') }}" class="block text-blue-600 hover:text-blue-900">Report Builder</a>
                <a href="{{ route('reports.custom.saved') }}" class="block text-blue-600 hover:text-blue-900">Saved Reports</a>
                <a href="{{ route('reports.custom.scheduled') }}" class="block text-blue-600 hover:text-blue-900">Scheduled Reports</a>
            </div>
        </div>

        <!-- Export Center -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Center</h3>
            <div class="space-y-2">
                <a href="{{ route('reports.exports.excel') }}" class="block text-blue-600 hover:text-blue-900">Export to Excel</a>
                <a href="{{ route('reports.exports.pdf') }}" class="block text-blue-600 hover:text-blue-900">Export to PDF</a>
                <a href="{{ route('reports.exports.bulk') }}" class="block text-blue-600 hover:text-blue-900">Bulk Exports</a>
            </div>
        </div>
    </div>
</div>
@endsection
