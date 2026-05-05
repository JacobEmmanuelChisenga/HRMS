@extends('layouts.main')

@section('title', 'Employee Details')
@section('page-title', 'Employee Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h2>
            <p class="text-gray-600 mt-1">{{ $employee->employee_number }}</p>
        </div>
        <div class="flex space-x-3 items-center">
            <a href="{{ route('employees.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back</a>
            <a href="{{ route('employees.edit', $employee->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
            @if(auth()->user()->hasRole('company_admin'))
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline-flex"
                      onsubmit="return confirm('Are you sure you want to permanently delete this employee? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Full Name</label>
                        <p class="text-gray-900 font-medium">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Email</label>
                        <p class="text-gray-900 font-medium">{{ $employee->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Phone</label>
                        <p class="text-gray-900 font-medium">{{ $employee->user->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Date of Birth</label>
                        <p class="text-gray-900 font-medium">{{ $employee->date_of_birth ? $employee->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Gender</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst($employee->gender ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">National ID</label>
                        <p class="text-gray-900 font-medium">{{ $employee->national_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Department</label>
                        <p class="text-gray-900 font-medium">{{ $employee->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Position</label>
                        <p class="text-gray-900 font-medium">{{ $employee->position->title ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Manager</label>
                        <p class="text-gray-900 font-medium">
                            @if($employee->manager)
                                {{ $employee->manager->first_name . ' ' . $employee->manager->last_name }}
                                <span class="text-xs text-gray-500">({{ $employee->manager->email }})</span>
                            @else
                                <span class="text-gray-400">Not Assigned</span>
                                @if($employee->department && $employee->department->manager)
                                    <span class="text-xs text-gray-500 block mt-1">
                                        Department Manager: {{ $employee->department->manager->first_name }} {{ $employee->department->manager->last_name }}
                                    </span>
                                @endif
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Hire Date</label>
                        <p class="text-gray-900 font-medium">{{ $employee->hire_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Employment Type</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $employee->employment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($employee->employment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Emergency Contacts -->
            @if($employee->emergencyContacts->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Emergency Contacts</h3>
                <div class="space-y-4">
                    @foreach($employee->emergencyContacts as $contact)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">{{ $contact->name }}</p>
                                <p class="text-sm text-gray-500">{{ $contact->relationship }}</p>
                                <p class="text-sm text-gray-700 mt-1">{{ $contact->phone }}</p>
                            </div>
                            @if($contact->is_primary)
                            <span class="px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">Primary</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                        {{ strtoupper(substr($employee->user->first_name, 0, 1) . substr($employee->user->last_name, 0, 1)) }}
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $employee->user->role->name ?? 'Employee' }}</p>
                    <span class="mt-3 inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $employee->employment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($employee->employment_status) }}
                    </span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Leave Requests</span>
                        <span class="font-medium">{{ $employee->leaveRequests->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Documents</span>
                        <span class="font-medium">{{ $employee->documents->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
