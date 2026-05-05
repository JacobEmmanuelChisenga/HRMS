@extends('layouts.main')

@section('title', 'Leave Types')
@section('page-title', 'Leave Types')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Leave Types</h2>
            <p class="text-gray-600 mt-1">
                @if(auth()->user()->hasRole('hr_manager'))
                    Manage company leave types and policies (cannot delete)
                @else
                    Manage company leave types and policies
                @endif
            </p>
        </div>
        @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
        <a href="{{ route('leave-types.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add Leave Type</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Allowed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Carries Forward</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leaveTypes as $leaveType)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $leaveType->name }}</div>
                            @if($leaveType->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($leaveType->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $leaveType->days_allowed }} days</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $leaveType->is_paid ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $leaveType->is_paid ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $leaveType->carries_forward ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $leaveType->carries_forward ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $leaveType->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($leaveType->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('leave-types.show', $leaveType->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                            <a href="{{ route('leave-types.edit', $leaveType->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <a href="{{ route('leave-types.policies', $leaveType->id) }}" class="text-purple-600 hover:text-purple-900">Policies</a>
                            @endif
                            @if(auth()->user()->hasRole('company_admin'))
                            <form action="{{ route('leave-types.destroy', $leaveType->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this leave type? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No leave types found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">{{ $leaveTypes->links() }}</div>
    </div>
</div>
@endsection
