@extends('layouts.main')

@section('title', 'Holiday Calendar')
@section('page-title', 'Holiday Calendar')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Holiday Calendar</h2>
            <p class="text-gray-600 mt-1">View holidays in calendar format</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('holidays.calendar') }}" class="flex items-center space-x-3">
                <input type="month" name="month" value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" 
                       class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Go
                </button>
            </form>
            <a href="{{ route('holidays.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Holidays
            </a>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-lg shadow p-6">
        @php
        $firstDay = Carbon\Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $startDate = $firstDay->copy()->startOfWeek();
        $endDate = $lastDay->copy()->endOfWeek();
        $currentDate = $startDate->copy();
        $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        @endphp

        <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">
            {{ $firstDay->format('F Y') }}
        </h3>

        <div class="grid grid-cols-7 gap-2">
            <!-- Day Headers -->
            @foreach($daysOfWeek as $day)
            <div class="text-center font-semibold text-gray-700 py-2">{{ $day }}</div>
            @endforeach

            <!-- Calendar Days -->
            @while($currentDate <= $endDate)
            @php
            $isCurrentMonth = $currentDate->month == $month;
            $isToday = $currentDate->isToday();
            $dayHolidays = $allHolidays->filter(function($holiday) use ($currentDate) {
                if ($holiday->is_recurring) {
                    return $holiday->date->format('m-d') == $currentDate->format('m-d');
                }
                return $holiday->date->format('Y-m-d') == $currentDate->format('Y-m-d');
            });
            @endphp
            <div class="border border-gray-200 rounded-lg p-2 min-h-[100px] {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }}">
                <div class="text-sm font-medium {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }}">
                    {{ $currentDate->day }}
                </div>
                <div class="mt-1 space-y-1">
                    @foreach($dayHolidays as $holiday)
                    <div class="text-xs bg-blue-100 text-blue-800 rounded px-1 py-0.5 truncate" title="{{ $holiday->name }}">
                        {{ $holiday->name }}
                    </div>
                    @endforeach
                </div>
            </div>
            @php $currentDate->addDay(); @endphp
            @endwhile
        </div>
    </div>

    <!-- Upcoming Holidays -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Upcoming Holidays</h3>
        <div class="space-y-2">
            @forelse($holidays->take(5) as $holiday)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">{{ $holiday->name }}</p>
                    <p class="text-sm text-gray-500">{{ $holiday->date->format('M d, Y') }}</p>
                </div>
                @if($holiday->is_recurring)
                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Recurring</span>
                @endif
            </div>
            @empty
            <p class="text-gray-500">No upcoming holidays</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
