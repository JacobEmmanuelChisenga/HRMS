@extends('layouts.main')

@section('title', 'Roles')
@section('page-title', 'Roles')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Roles</h2>
            <p class="text-gray-600 mt-1">Manage custom roles for your company</p>
        </div>
        <a href="{{ route('settings.roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Create Role
        </a>
    </div>

    <!-- System Roles Info -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>System Roles:</strong> Super Admin, Company Admin, HR Manager, Manager, and Employee are system roles that cannot be modified or deleted. You can create custom roles below for your company's specific needs.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $role->name }}</div>
                            <div class="text-sm text-gray-500">{{ $role->slug }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($role->company_id === null)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">System Role</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Custom Role</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $role->description ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $role->users_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                {{ $role->permissions->count() }} permissions
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('settings.roles.show', $role->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if($role->company_id !== null)
                                <a href="{{ route('settings.roles.edit', $role->id) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                @if($role->users_count == 0)
                                    <form action="{{ route('settings.roles.destroy', $role->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400" title="Cannot delete role with assigned users">Delete</span>
                                @endif
                            @else
                                <span class="text-gray-400" title="System roles cannot be edited">Edit</span>
                                <span class="text-gray-400" title="System roles cannot be deleted">Delete</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
