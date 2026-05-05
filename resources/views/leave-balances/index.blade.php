@extends('layouts.main')

@section('title', 'Leave Balances')
@section('page-title', 'Leave Balances')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Employee Leave Balances</h2>
            <p class="text-gray-600 mt-1">View and manage leave balances for {{ $currentYear }}</p>
        </div>
        @if(auth()->user()->hasRole('company_admin'))
        <a href="{{ route('leave-balances.bulk-adjustments') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Bulk Adjustments</a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leave Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Used Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pending Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remaining Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($balances as $balance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $balance->employee->user->first_name }} {{ $balance->employee->user->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $balance->employee->employee_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->leaveType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->total_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->used_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->pending_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $balance->remaining_days > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $balance->remaining_days }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('leave-balances.show', $balance->employee->id) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No leave balances found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">{{ $balances->links() }}</div>
    </div>
</div>
@endsection
