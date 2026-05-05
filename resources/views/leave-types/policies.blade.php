@extends('layouts.main')

@section('title', 'Configure Leave Policies')
@section('page-title', 'Configure Leave Policies')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('leave-types.policies.update', $leaveType->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave Type: {{ $leaveType->name }}</h3>
                <p class="text-gray-600 mb-6">Configure policies and settings for this leave type</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Settings</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_approval" value="1" {{ old('requires_approval', $leaveType->requires_approval) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Requires Manager/HR Approval</span>
                    </label>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Settings</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_paid" value="1" {{ old('is_paid', $leaveType->is_paid) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Is Paid Leave</span>
                    </label>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Carry Forward Settings</h3>
                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="carries_forward" value="1" {{ old('carries_forward', $leaveType->carries_forward) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Allow Carry Forward to Next Year</span>
                    </label>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Carry Forward Days</label>
                        <input type="number" name="max_carry_forward_days" value="{{ old('max_carry_forward_days', $leaveType->max_carry_forward_days) }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Maximum number of days that can be carried forward</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Requirements</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_document" value="1" {{ old('requires_document', $leaveType->requires_document) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Requires Supporting Document</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('leave-types.show', $leaveType->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Policies</button>
            </div>
        </form>
    </div>
</div>
@endsection
