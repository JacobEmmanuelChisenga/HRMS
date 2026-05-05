@extends('layouts.main')

@section('title', 'Department Details')
@section('page-title', 'Department Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $department->name }}</h2>
            <p class="text-gray-600 mt-1">
                @if($department->manager)
                    Managed by {{ $department->manager->first_name }} {{ $department->manager->last_name }}
                @else
                    No manager assigned
                @endif
            </p>
        </div>
        <div class="flex space-x-3">
            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
            <a href="{{ route('departments.edit', $department->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
            @endif
            @if(auth()->user()->hasRole('company_admin'))
            <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Delete</button>
            </form>
            @endif
            <a href="{{ route('departments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Department Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Department Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Name</label>
                        <p class="text-gray-900 font-medium">{{ $department->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $department->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($department->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Manager</label>
                        <p class="text-gray-900 font-medium">
                            @if($department->manager)
                                {{ $department->manager->first_name }} {{ $department->manager->last_name }}
                                <span class="text-gray-500 text-sm">({{ $department->manager->email }})</span>
                            @else
                                <span class="text-gray-400">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Total Employees</label>
                        <p class="text-gray-900 font-medium">{{ $department->employees->count() }}</p>
                    </div>
                    @if($department->description)
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1">{{ $department->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Positions in this Department -->
            @if($department->positions->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Positions ({{ $department->positions->count() }})</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($department->positions as $position)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $position->title }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($position->level) }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $position->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($position->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('positions.show', $position->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No positions</h3>
                    <p class="mt-1 text-sm text-gray-500">This department doesn't have any positions yet.</p>
                    @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                    <div class="mt-6">
                        <a href="{{ route('positions.create') }}?department={{ $department->id }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Add Position
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Employees in this Department -->
            @if($department->employees->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employees ({{ $department->employees->count() }})</h3>
                <div class="space-y-3">
                    @foreach($department->employees as $employee)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($employee->user->first_name ?? 'U', 0, 1) . substr($employee->user->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $employee->user->email }}</p>
                                @if($employee->position)
                                <p class="text-xs text-gray-400">{{ $employee->position->title }}</p>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('employees.show', $employee->id) }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No employees</h3>
                    <p class="mt-1 text-sm text-gray-500">This department doesn't have any employees yet.</p>
                    @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                    <div class="mt-6">
                        <a href="{{ route('employees.create') }}?department={{ $department->id }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Add Employee
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Employees</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $department->employees->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Positions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $department->positions->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active Positions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $department->positions->where('status', 'active')->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-sm font-medium text-gray-900">{{ $department->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Manager Info -->
            @if($department->manager)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Department Manager</h3>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($department->manager->first_name ?? 'M', 0, 1) . substr($department->manager->last_name ?? 'N', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $department->manager->first_name }} {{ $department->manager->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ $department->manager->email }}</p>
                        <p class="text-xs text-gray-400">{{ $department->manager->role->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
