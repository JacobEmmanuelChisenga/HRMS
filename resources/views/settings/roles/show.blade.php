@extends('layouts.main')

@section('title', 'Role Details')
@section('page-title', 'Role Details')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $role->name }}</h2>
            <p class="text-gray-600 mt-1">
                @if($role->company_id === null)
                    System Role
                @else
                    Custom Company Role
                @endif
            </p>
        </div>
        <div class="flex space-x-3">
            @if($role->company_id !== null)
                <a href="{{ route('settings.roles.edit', $role->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
            @endif
            <a href="{{ route('settings.roles.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Role Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Name</label>
                        <p class="text-gray-900 font-medium">{{ $role->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Slug</label>
                        <p class="text-gray-900 font-medium">{{ $role->slug }}</p>
                    </div>
                    @if($role->description)
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Description</label>
                        <p class="text-gray-900 mt-1">{{ $role->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Permissions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Permissions ({{ $role->permissions->count() }})</h3>
                <div class="space-y-4">
                    @foreach($permissions as $category => $perms)
                        @php
                            $categoryPermissions = $perms->filter(function($p) use ($role) {
                                return $role->permissions->contains($p->id);
                            });
                        @endphp
                        @if($categoryPermissions->count() > 0)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ ucfirst($category) }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($categoryPermissions as $permission)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Users with this role</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $role->users->where('company_id', auth()->user()->company_id)->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Permissions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $role->permissions->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ $role->created_at ? $role->created_at->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            @if($role->company_id !== null && $role->users->where('company_id', auth()->user()->company_id)->count() == 0)
            <!-- Delete Option -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Danger Zone</h3>
                <form action="{{ route('settings.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete Role
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
