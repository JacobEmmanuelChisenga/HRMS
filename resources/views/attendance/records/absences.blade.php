@extends('layouts.main')

@section('title', 'Absences')
@section('page-title', 'Absences')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Absences</h2>
            <p class="text-gray-600 mt-1">View all employee absences</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('attendance.records.absences') }}" class="flex space-x-3">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </form>
            <a href="{{ route('attendance.records.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                All Records
            </a>
        </div>
    </div>

    <!-- Absences Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent Days</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absences as $absence)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $absence['employee']->user->first_name }} {{ $absence['employee']->user->last_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $absence['employee']->employee_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $absence['count'] }} days
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($absence['dates'], 0, 5) as $date)
                                <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ Carbon\Carbon::parse($date)->format('M d') }}</span>
                                @endforeach
                                @if(count($absence['dates']) > 5)
                                <span class="px-2 py-1 bg-gray-100 rounded text-xs">+{{ count($absence['dates']) - 5 }} more</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No absences found for the selected period</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
