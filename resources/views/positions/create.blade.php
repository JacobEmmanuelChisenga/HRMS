@extends('layouts.main')

@section('title', 'Add Position')
@section('page-title', 'Add Position')

@section('content')
<div class="max-w-4xl mx-auto">
    @if($departments->count() == 0)
    <!-- Guided Setup Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">
                    Setup Required: Create Department First
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p class="mb-2">A position must belong to a department. Please create a department first.</p>
                    <a href="{{ route('departments.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Create Department
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('positions.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Position Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Position Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Software Developer, Sales Manager">
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <div class="flex space-x-2">
                            <select name="department_id" required class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" {{ $departments->count() == 0 ? 'disabled' : '' }}>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                                @endforeach
                            </select>
                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                                <a href="{{ route('departments.create') }}" target="_blank" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm whitespace-nowrap" title="Create New Department">
                                    + New
                                </a>
                            @endif
                        </div>
                        @error('department_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-1">Select which department this position belongs to</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Level *</label>
                        <select name="level" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Level</option>
                            <option value="entry" {{ old('level') == 'entry' ? 'selected' : '' }}>Entry</option>
                            <option value="junior" {{ old('level') == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('level') == 'mid' ? 'selected' : '' }}>Mid</option>
                            <option value="senior" {{ old('level') == 'senior' ? 'selected' : '' }}>Senior</option>
                            <option value="lead" {{ old('level') == 'lead' ? 'selected' : '' }}>Lead</option>
                            <option value="executive" {{ old('level') == 'executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                        @error('level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('positions.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Position
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
