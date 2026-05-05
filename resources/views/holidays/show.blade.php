@extends('layouts.main')

@section('title', 'Holiday Details')
@section('page-title', 'Holiday Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $holiday->name }}</h2>
                <p class="text-gray-600 mt-1">Holiday Information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('holidays.edit', $holiday->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Edit
                </a>
                <a href="{{ route('holidays.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Back
                </a>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Holiday Name</label>
                <p class="text-gray-900">{{ $holiday->name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <p class="text-gray-900">{{ $holiday->date->format('F d, Y') }} ({{ $holiday->date->format('l') }})</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Recurring</label>
                <p class="text-gray-900">
                    @if($holiday->is_recurring)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">Yes - Repeats every year</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">No - One-time holiday</span>
                    @endif
                </p>
            </div>

            @if($holiday->description)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <p class="text-gray-900">{{ $holiday->description }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                <p class="text-gray-900">{{ $holiday->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
