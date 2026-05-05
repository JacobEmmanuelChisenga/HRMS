@extends('layouts.main')

@section('title', 'Billing History')
@section('page-title', 'Billing History')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Billing History</h2>
            <p class="text-gray-600 mt-1">View your billing and invoice history</p>
        </div>
        <a href="{{ route('settings.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Settings
        </a>
    </div>

    <!-- Billing History -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Invoice History</h3>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No billing history</h3>
                <p class="mt-1 text-sm text-gray-500">Billing history will appear here once invoices are generated.</p>
            </div>
        </div>
    </div>
</div>
@endsection
