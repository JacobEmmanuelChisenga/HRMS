@extends('layouts.main')

@section('title', 'Roles & Permissions')
@section('page-title', 'Roles & Permissions')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Roles & Permissions</h2>
            <p class="text-gray-600 mt-1">Manage user roles and their permissions</p>
        </div>
        <a href="{{ route('settings.users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Users
        </a>
    </div>

    <!-- Roles -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Roles</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permissions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $role->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $role->users()->where('company_id', auth()->user()->company_id)->count() }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                {{ $role->permissions->count() }} permissions
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permissions by Category -->
    <div class="space-y-4">
        @foreach($permissions as $category => $perms)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($category) }}</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($perms as $permission)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
