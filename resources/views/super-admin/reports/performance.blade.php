@extends('layouts.main')

@section('title', 'Performance Metrics')
@section('page-title', 'Performance Metrics')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-500">Total Companies</p>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $performance['total_companies'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-500">Active Companies</p>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $performance['active_companies'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-500">Suspended Companies</p>
            <p class="text-2xl font-semibold text-gray-900 mt-2">{{ $performance['suspended_companies'] }}</p>
        </div>
    </div>
</div>
@endsection
