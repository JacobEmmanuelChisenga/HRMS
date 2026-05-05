@extends('layouts.main')

@section('title', 'Report Builder')
@section('page-title', 'Report Builder')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Report Builder</h2>
            <p class="text-gray-600 mt-1">Create custom reports</p>
        </div>
        <a href="{{ route('reports.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Reports
        </a>
    </div>

    <!-- Builder Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="#" class="space-y-6">
            @csrf
            <div>
                <label for="report_name" class="block text-sm font-medium text-gray-700 mb-2">Report Name *</label>
                <input type="text" name="report_name" id="report_name" required
                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">Report Type *</label>
                <select name="report_type" id="report_type" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select Type</option>
                    @foreach($reportTypes as $type)
                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filters</label>
                <div class="space-y-2">
                    <p class="text-sm text-gray-500">Custom report builder coming soon. Select filters and parameters to build your report.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Generate Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
