@extends('layouts.main')

@section('title', 'Attendance Records')
@section('page-title', 'Attendance Records')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">All Attendance Records</h2>
            <p class="text-gray-600 mt-1">View and filter attendance records</p>
        </div>
        <a href="{{ route('attendance.today') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Today's Attendance
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('attendance.records.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                <select name="employee_id" id="employee_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->user->first_name }} {{ $employee->user->last_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Date To</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                    <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                    <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>
            </div>
            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('attendance.records.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Clear
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($records as $record)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $record->employee->user->first_name }} {{ $record->employee->user->last_name }}</div>
                            <div class="text-sm text-gray-500">{{ $record->employee->employee_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->clock_in ? Carbon\Carbon::parse($record->clock_in)->format('h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $record->clock_out ? Carbon\Carbon::parse($record->clock_out)->format('h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->hours_worked ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'present' => 'bg-green-100 text-green-800',
                                    'late' => 'bg-yellow-100 text-yellow-800',
                                    'absent' => 'bg-red-100 text-red-800',
                                    'half_day' => 'bg-orange-100 text-orange-800',
                                    'on_leave' => 'bg-blue-100 text-blue-800',
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$record->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('attendance.records.edit', $record->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No attendance records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
