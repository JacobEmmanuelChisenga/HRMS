@extends('layouts.main')

@section('title', 'Position Details')
@section('page-title', 'Position Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $position->title }}</h2>
            <p class="text-gray-600 mt-1">{{ $position->department->name ?? 'No Department' }} • {{ ucfirst($position->level) }} Level</p>
        </div>
        <div class="flex space-x-3">
            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
            <a href="{{ route('positions.edit', $position->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
            @endif
            @if(auth()->user()->hasRole('company_admin'))
            <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this position? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Delete</button>
            </form>
            @endif
            <a href="{{ route('positions.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Position Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Position Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Title</label>
                        <p class="text-gray-900 font-medium">{{ $position->title }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Department</label>
                        <p class="text-gray-900 font-medium">{{ $position->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Level</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst($position->level) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $position->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($position->status) }}
                        </span>
                    </div>
                    @if($position->description)
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1">{{ $position->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Employees in this Position -->
            @if($position->employees->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employees ({{ $position->employees->count() }})</h3>
                <div class="space-y-3">
                    @foreach($position->employees as $employee)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($employee->user->first_name ?? 'U', 0, 1) . substr($employee->user->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $employee->user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('employees.show', $employee->id) }}" class="text-blue-600 hover:text-blue-700 text-sm">View</a>
                    </div>
                    @endforeach
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
                        <p class="text-2xl font-bold text-gray-900">{{ $position->employees->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-sm font-medium text-gray-900">{{ $position->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
