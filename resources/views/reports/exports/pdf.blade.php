@extends('layouts.main')

@section('title', 'Export to PDF')
@section('page-title', 'Export to PDF')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Export to PDF</h2>
            <p class="text-gray-600 mt-1">Export data to PDF format</p>
        </div>
        <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Reports
        </a>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="#" class="space-y-6">
            <div>
                <label for="export_type" class="block text-sm font-medium text-gray-700 mb-2">Export Type *</label>
                <select name="export_type" id="export_type" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="employee">Employee Data</option>
                    <option value="attendance">Attendance Records</option>
                    <option value="leave">Leave Requests</option>
                    <option value="document">Documents</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="date" name="date_from" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <input type="date" name="date_to" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Export to PDF
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
