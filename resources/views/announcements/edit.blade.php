@extends('layouts.main')

@section('title', 'Edit Announcement')
@section('page-title', 'Edit Announcement')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Announcement</h2>
            <p class="text-gray-600 mt-1">Update announcement information</p>
        </div>
        <a href="{{ route('announcements.all') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Announcements
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                    <textarea name="content" id="content" rows="10" required
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Publish Date -->
                <div>
                    <label for="publish_date" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                    <input type="date" name="publish_date" id="publish_date" 
                           value="{{ old('publish_date', $announcement->publish_date ? Carbon\Carbon::parse($announcement->publish_date)->format('Y-m-d') : '') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to publish immediately</p>
                    @error('publish_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiry Date -->
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" 
                           value="{{ old('expiry_date', $announcement->expiry_date ? Carbon\Carbon::parse($announcement->expiry_date)->format('Y-m-d') : '') }}"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty for no expiry</p>
                    @error('expiry_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Save as Draft -->
                <div class="flex items-center">
                    <input type="checkbox" name="save_as_draft" id="save_as_draft" value="1"
                           {{ old('save_as_draft', !$announcement->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="save_as_draft" class="ml-2 block text-sm text-gray-900">
                        Save as draft (will not be published)
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('announcements.all') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" name="save_as_draft" value="0" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Update Announcement
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
