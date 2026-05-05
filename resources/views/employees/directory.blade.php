@extends('layouts.main')

@section('title', 'Employee Directory')
@section('page-title', 'Employee Directory')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Employee Directory</h2>
            <p class="text-gray-600 mt-1">Browse all active employees in your company</p>
        </div>
    </div>

    <!-- Employee Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($employees as $employee)
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold text-xl mb-4">
                    {{ strtoupper(substr($employee->user->first_name ?? 'U', 0, 1) . substr($employee->user->last_name ?? 'S', 0, 1)) }}
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h3>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $employee->position->name ?? ($employee->user->role->name ?? 'N/A') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ $employee->department->name ?? 'N/A' }}</p>
                <div class="mt-4 w-full">
                    <a href="{{ route('employees.show', $employee->id) }}" class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                        View Profile
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">No employees found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
