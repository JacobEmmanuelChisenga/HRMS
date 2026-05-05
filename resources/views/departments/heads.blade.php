@extends('layouts.main')

@section('title', 'Department Heads')
@section('page-title', 'Department Heads')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Department Heads</h2>
            <p class="text-gray-600 mt-1">View all department managers and heads</p>
        </div>
        <a href="{{ route('departments.index') }}" class="text-blue-600 hover:text-blue-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Departments</span>
        </a>
    </div>

    <!-- Department Heads Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($departments as $department)
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $department->name }}</h3>
                    @if($department->manager)
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($department->manager->first_name ?? 'M', 0, 1) . substr($department->manager->last_name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $department->manager->first_name }} {{ $department->manager->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $department->manager->email }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                @if($department->manager->employeeProfile && $department->manager->employeeProfile->position)
                                    {{ $department->manager->employeeProfile->position->title }}
                                @elseif($department->manager->role)
                                    {{ $department->manager->role->name }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    @else
                    <div class="text-sm text-gray-500 italic">No manager assigned</div>
                    @endif
                </div>
            </div>
            @if($department->description)
            <p class="text-sm text-gray-600 mt-4 line-clamp-2">{{ $department->description }}</p>
            @endif
            <div class="mt-4 flex space-x-2">
                <a href="{{ route('departments.show', $department->id) }}" class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                    View Department
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">No departments with assigned heads found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
