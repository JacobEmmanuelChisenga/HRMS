@extends('layouts.main')

@section('title', 'Edit Department')
@section('page-title', 'Edit Department')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('departments.update', $department->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Department Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Department Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $department->name) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Manager <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <select name="manager_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">No Manager</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('manager_id', $department->manager_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }} 
                                @if($user->employeeProfile && $user->employeeProfile->department_id == $department->id)
                                    ({{ $user->email }} - In this department)
                                @else
                                    ({{ $user->email }})
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('manager_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-1">
                            💡 <strong>Tip:</strong> Select an employee who belongs to this department to be the manager. 
                            You can also assign a manager role to an employee first, then assign them here.
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('status', $department->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $department->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $department->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('departments.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Department
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
