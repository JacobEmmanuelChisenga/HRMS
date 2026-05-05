@extends('layouts.main')

@section('title', 'Bulk Exports')
@section('page-title', 'Bulk Exports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Bulk Exports</h2>
            <p class="text-gray-600 mt-1">Export multiple reports at once</p>
        </div>
        <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Reports
        </a>
    </div>

    <!-- Bulk Export Options -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Reports to Export</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="reports[]" value="employee" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-900">Employee Reports</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="reports[]" value="attendance" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-900">Attendance Reports</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="reports[]" value="leave" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-900">Leave Reports</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="reports[]" value="document" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-900">Document Reports</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="export_format" class="block text-sm font-medium text-gray-700 mb-2">Export Format *</label>
                <select name="export_format" id="export_format" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="excel">Excel</option>
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                </select>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Export All Selected
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
