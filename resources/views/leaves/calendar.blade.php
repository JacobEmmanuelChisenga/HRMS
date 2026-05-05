@extends('layouts.main')

@section('title', 'Leave Calendar')
@section('page-title', 'Leave Calendar')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Leave Calendar</h2>
        <p class="text-gray-600 mt-1">View all approved and pending leaves on calendar</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div id="calendar"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const events = @json($events);
    
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events.map(event => ({
            title: event.title,
            start: event.start,
            end: event.end,
            backgroundColor: event.color,
            borderColor: event.color,
            url: event.url
        })),
        eventClick: function(info) {
            if (info.event.url) {
                window.location.href = info.event.url;
                return false;
            }
        }
    });
    
    calendar.render();
});
</script>
@endpush

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
@endpush
@endsection
