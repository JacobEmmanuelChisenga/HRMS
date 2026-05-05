@extends('layouts.main')

@section('title', 'Employee Leave Balances')
@section('page-title', 'Employee Leave Balances')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h2>
            <p class="text-gray-600 mt-1">Leave balances for {{ $currentYear }}</p>
        </div>
        <a href="{{ route('leave-balances.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $balance->leaveType->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->total_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->used_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $balance->pending_days }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $balance->remaining_days > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $balance->remaining_days }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showAdjustModal({{ $balance->leave_type_id }}, '{{ $balance->leaveType->name }}')" class="text-blue-600 hover:text-blue-900">Adjust</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No leave balances found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Adjust Modal -->
<div id="adjustModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="{{ route('leave-balances.adjust', $employee->id) }}" method="POST">
            @csrf
            <input type="hidden" name="leave_type_id" id="modal_leave_type_id">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Adjust Leave Balance</h3>
            <p class="text-sm text-gray-600 mb-4" id="modal_leave_type_name"></p>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adjustment Type</label>
                    <select name="adjustment_type" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="add">Add Days</option>
                        <option value="subtract">Subtract Days</option>
                        <option value="set">Set Total Days</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Days</label>
                    <input type="number" name="days" min="0" step="0.5" required class="w-full border border-gray-300 rounded-lg px-4 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason (Optional)</label>
                    <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2"></textarea>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeAdjustModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Adjust</button>
            </div>
        </form>
    </div>
</div>

<script>
function showAdjustModal(leaveTypeId, leaveTypeName) {
    document.getElementById('modal_leave_type_id').value = leaveTypeId;
    document.getElementById('modal_leave_type_name').textContent = 'Leave Type: ' + leaveTypeName;
    document.getElementById('adjustModal').classList.remove('hidden');
}

function closeAdjustModal() {
    document.getElementById('adjustModal').classList.add('hidden');
}
</script>
@endsection
