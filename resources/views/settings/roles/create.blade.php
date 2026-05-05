@extends('layouts.main')

@section('title', 'Create Role')
@section('page-title', 'Create Role')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('settings.roles.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Role Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Senior Manager, Team Lead">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Auto-generated from name">
                        @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate from name</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the role's responsibilities">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Permissions</h3>
                <p class="text-sm text-gray-600 mb-4">Select the permissions for this role</p>
                
                <div class="space-y-6">
                    @foreach($permissions as $category => $perms)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-3">{{ ucfirst($category) }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($perms as $permission)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('permissions')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('settings.roles.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Role</button>
            </div>
        </form>
    </div>
</div>
@endsection
