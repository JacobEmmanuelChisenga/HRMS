@extends('layouts.main')

@section('title', 'Edit Leave Type')
@section('page-title', 'Edit Leave Type')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('leave-types.update', $leaveType->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Leave Type Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $leaveType->name) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $leaveType->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Days Allowed *</label>
                        <input type="number" name="days_allowed" value="{{ old('days_allowed', $leaveType->days_allowed) }}" min="0" max="365" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('days_allowed')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status', $leaveType->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $leaveType->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Carry Forward Days</label>
                        <input type="number" name="max_carry_forward_days" value="{{ old('max_carry_forward_days', $leaveType->max_carry_forward_days) }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('max_carry_forward_days')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Policies</h3>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_approval" value="1" {{ old('requires_approval', $leaveType->requires_approval) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Requires Approval</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_paid" value="1" {{ old('is_paid', $leaveType->is_paid) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Is Paid Leave</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="carries_forward" value="1" {{ old('carries_forward', $leaveType->carries_forward) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Carries Forward to Next Year</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_document" value="1" {{ old('requires_document', $leaveType->requires_document) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Requires Supporting Document</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('leave-types.show', $leaveType->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Leave Type</button>
            </div>
        </form>
    </div>
</div>
@endsection
