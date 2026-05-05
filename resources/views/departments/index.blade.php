@extends('layouts.main')

@section('title', 'Departments')
@section('page-title', 'Departments')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Departments</h2>
            <p class="text-gray-600 mt-1">
                @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('employee'))
                    View company departments
                @elseif(auth()->user()->hasRole('hr_manager'))
                    Manage company departments (cannot delete)
                @else
                    Manage company departments
                @endif
            </p>
        </div>
        @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
        <a href="{{ route('departments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add Department</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manager</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($departments as $department)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $department->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $department->manager ? $department->manager->first_name . ' ' . $department->manager->last_name : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $department->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($department->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('departments.show', $department->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                                <a href="{{ route('departments.edit', $department->id) }}" class="text-green-600 hover:text-green-900">Edit</a>
                            @endif
                            @if(auth()->user()->hasRole('company_admin'))
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this department? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No departments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t">{{ $departments->links() }}</div>
    </div>
</div>
@endsection
