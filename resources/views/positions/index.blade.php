@extends('layouts.main')

@section('title', 'Positions')
@section('page-title', 'Positions')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Positions</h2>
            <p class="text-gray-600 mt-1">
                @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('employee'))
                    View job positions
                @elseif(auth()->user()->hasRole('hr_manager'))
                    Manage job positions (cannot delete)
                @else
                    Manage job positions
                @endif
            </p>
        </div>
        @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
        <a href="{{ route('positions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Add Position</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($positions as $position)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $position->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $position->department->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($position->level) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $position->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($position->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('positions.show', $position->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                                <a href="{{ route('positions.edit', $position->id) }}" class="text-green-600 hover:text-green-900">Edit</a>
                            @endif
                            @if(auth()->user()->hasRole('company_admin'))
                                <form action="{{ route('positions.destroy', $position->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this position? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No positions found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t">{{ $positions->links() }}</div>
    </div>
</div>
@endsection
