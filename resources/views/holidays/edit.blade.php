@extends('layouts.main')

@section('title', 'Edit Holiday')
@section('page-title', 'Edit Holiday')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Edit Holiday</h2>
            <p class="text-gray-600 mt-1">Update holiday information</p>
        </div>

        <form action="{{ route('holidays.update', $holiday->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Holiday Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Holiday Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" 
                    value="{{ old('name', $holiday->name) }}"
                    required
                    placeholder="e.g., New Year's Day, Christmas, Independence Day"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Selection with Calendar -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                    Holiday Date <span class="text-red-500">*</span>
                </label>
                
                <!-- Calendar Display -->
                <div class="mb-4 bg-gray-50 rounded-lg p-4">
                    <div id="calendar-container" class="max-w-md mx-auto"></div>
                </div>
                
                <!-- Date Input -->
                <div class="relative">
                    <input type="date" name="date" id="date" 
                        value="{{ old('date', $holiday->date->format('Y-m-d')) }}"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Click the calendar above or use the date picker to select a date</p>
                </div>
                @error('date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Recurring Holiday -->
            <div class="flex items-center">
                <input type="checkbox" name="is_recurring" id="is_recurring" value="1" 
                    {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="is_recurring" class="ml-2 text-sm font-medium text-gray-700">
                    Recurring Holiday
                </label>
                <p class="ml-3 text-xs text-gray-500">(This holiday will repeat every year)</p>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description (Optional)
                </label>
                <textarea name="description" id="description" rows="3"
                    placeholder="Add any additional information about this holiday..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $holiday->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('holidays.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Holiday
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .calendar-day {
        cursor: pointer;
        transition: all 0.2s;
    }
    .calendar-day:hover {
        background-color: #e5e7eb;
    }
    .calendar-day.selected {
        background-color: #3b82f6;
        color: white;
    }
    .calendar-day.today {
        border: 2px solid #3b82f6;
    }
    .calendar-day.other-month {
        color: #9ca3af;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const calendarContainer = document.getElementById('calendar-container');
    
    let currentDate = new Date();
    if (dateInput.value) {
        currentDate = new Date(dateInput.value);
    }
    
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - startDate.getDay()); // Start from Sunday
        
        const endDate = new Date(lastDay);
        endDate.setDate(endDate.getDate() + (6 - endDate.getDay())); // End on Saturday
        
        const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                           'July', 'August', 'September', 'October', 'November', 'December'];
        
        let html = `
            <div class="mb-4 flex items-center justify-between">
                <button type="button" onclick="changeMonth(-1)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                    ← Prev
                </button>
                <h3 class="text-lg font-semibold text-gray-900">${monthNames[month]} ${year}</h3>
                <button type="button" onclick="changeMonth(1)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                    Next →
                </button>
            </div>
            <div class="grid grid-cols-7 gap-1">
        `;
        
        // Day headers
        daysOfWeek.forEach(day => {
            html += `<div class="text-center text-xs font-semibold text-gray-600 py-2">${day}</div>`;
        });
        
        // Calendar days
        const current = new Date(startDate);
        const selectedDate = dateInput.value ? new Date(dateInput.value) : null;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        while (current <= endDate) {
            const isCurrentMonth = current.getMonth() === month;
            const isToday = current.getTime() === today.getTime();
            const isSelected = selectedDate && current.toDateString() === selectedDate.toDateString();
            const dateStr = current.toISOString().split('T')[0];
            
            let classes = 'calendar-day text-center py-2 rounded text-sm';
            if (!isCurrentMonth) classes += ' other-month';
            if (isToday) classes += ' today';
            if (isSelected) classes += ' selected';
            
            html += `
                <div class="${classes}" 
                     onclick="selectDate('${dateStr}')"
                     data-date="${dateStr}">
                    ${current.getDate()}
                </div>
            `;
            
            current.setDate(current.getDate() + 1);
        }
        
        html += '</div>';
        calendarContainer.innerHTML = html;
    }
    
    window.selectDate = function(dateStr) {
        dateInput.value = dateStr;
        renderCalendar();
    };
    
    window.changeMonth = function(direction) {
        currentDate.setMonth(currentDate.getMonth() + direction);
        renderCalendar();
    };
    
    // Update calendar when date input changes
    dateInput.addEventListener('change', function() {
        if (this.value) {
            currentDate = new Date(this.value);
            renderCalendar();
        }
    });
    
    // Initial render
    renderCalendar();
});
</script>
@endpush
@endsection
