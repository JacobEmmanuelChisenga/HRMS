@extends('layouts.main')

@section('title', 'Bulk Leave Adjustments')
@section('page-title', 'Bulk Leave Adjustments')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('leave-balances.bulk-adjustments.process') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Leave Balance Adjustment</h3>
                <p class="text-gray-600 mb-6">Adjust leave balances for multiple employees at once</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type *</label>
                    <select name="leave_type_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Leave Type</option>
                        @foreach($leaveTypes as $leaveType)
                        <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                        @endforeach
                    </select>
                    @error('leave_type_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adjustment Type *</label>
                    <select name="adjustment_type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="add">Add Days</option>
                        <option value="subtract">Subtract Days</option>
                        <option value="set">Set Total Days</option>
                    </select>
                    @error('adjustment_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Days *</label>
                    <input type="number" name="days" min="0" step="0.5" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('days')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Employees *</label>
                <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                    <label class="flex items-center mb-3 pb-3 border-b">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm font-medium text-gray-700">Select All</span>
                    </label>
                    @foreach($employees as $employee)
                    <label class="flex items-center py-2 hover:bg-gray-50 rounded px-2">
                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="employee-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">{{ $employee->user->first_name }} {{ $employee->user->last_name }} ({{ $employee->employee_number }})</span>
                    </label>
                    @endforeach
                </div>
                @error('employee_ids')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason (Optional)</label>
                <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('leave-balances.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Process Adjustments</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});
</script>
@endsection
