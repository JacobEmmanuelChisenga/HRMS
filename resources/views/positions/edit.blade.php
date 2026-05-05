@extends('layouts.main')

@section('title', 'Edit Position')
@section('page-title', 'Edit Position')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('positions.update', $position->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Position Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Position Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $position->title) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select name="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $position->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Level *</label>
                        <select name="level" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Level</option>
                            <option value="entry" {{ old('level', $position->level) == 'entry' ? 'selected' : '' }}>Entry</option>
                            <option value="junior" {{ old('level', $position->level) == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ old('level', $position->level) == 'mid' ? 'selected' : '' }}>Mid</option>
                            <option value="senior" {{ old('level', $position->level) == 'senior' ? 'selected' : '' }}>Senior</option>
                            <option value="lead" {{ old('level', $position->level) == 'lead' ? 'selected' : '' }}>Lead</option>
                            <option value="executive" {{ old('level', $position->level) == 'executive' ? 'selected' : '' }}>Executive</option>
                        </select>
                        @error('level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status', $position->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $position->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $position->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('positions.show', $position->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Position
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
