@extends('layouts.main')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->first_name }}!</h2>
        <p class="text-blue-100">Here's what's happening with your account today.</p>
    </div>

    <!-- Super Admin Dashboard Sections -->
    @if(auth()->user()->hasRole('super_admin') && isset($stats['section']))
        @if($stats['section'] === 'companies')
            <!-- All Companies Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">All Companies Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if(isset($stats['companies_by_status']))
                        @foreach($stats['companies_by_status'] as $status)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-600 capitalize">{{ $status->status }}</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $status->count }}</p>
                        </div>
                        @endforeach
                    @endif
                </div>
                @if(isset($stats['companies_by_plan']))
                <div class="mt-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-3">Companies by Subscription Plan</h4>
                    <div class="space-y-2">
                        @foreach($stats['companies_by_plan'] as $plan)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 capitalize">{{ $plan->subscription_plan }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $plan->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        @elseif($stats['section'] === 'revenue')
            <!-- Revenue Analytics -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Analytics</h3>
                @if(isset($stats['total_revenue']))
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-600">Total Monthly Recurring Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>
                @endif
                @if(isset($stats['revenue_by_plan']))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($stats['revenue_by_plan'] as $plan => $revenue)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600 capitalize">{{ $plan }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">${{ number_format($revenue, 2) }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        @else
            <!-- System Overview (Default) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($stats['total_companies']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Companies</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_companies'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($stats['active_companies']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Active Companies</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_companies'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($stats['total_users']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Users</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif
    @elseif(auth()->user()->hasRole('company_admin') && isset($stats['section']))
        @if($stats['section'] === 'stats')
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if(isset($stats['active_employees']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Active Employees</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_employees'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['total_departments']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Total Departments</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_departments'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['total_positions']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Total Positions</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_positions'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['approved_leaves']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Approved Leaves (This Month)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['approved_leaves'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        @elseif($stats['section'] === 'activities')
            <!-- Recent Activities -->
            <div class="space-y-6">
                @if(isset($stats['recent_leaves']) && $stats['recent_leaves']->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Leave Requests</h3>
                    <div class="space-y-3">
                        @foreach($stats['recent_leaves'] as $leave)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $leave->employee->user->first_name }} {{ $leave->employee->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $leave->leaveType->name ?? 'N/A' }} - {{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : ($leave->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($stats['recent_employees']) && $stats['recent_employees']->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recently Added Employees</h3>
                    <div class="space-y-3">
                        @foreach($stats['recent_employees'] as $employee)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($employee->user->first_name ?? 'U', 0, 1) . substr($employee->user->last_name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $employee->department->name ?? 'N/A' }} - {{ $employee->position->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        @else
            <!-- Company Overview (Default) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($stats['total_employees']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Employees</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_employees'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($stats['pending_leaves']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pending Leaves</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_leaves'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($stats['today_attendance']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Today's Attendance</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['today_attendance'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif
    @elseif(auth()->user()->hasRole('hr_manager') && isset($stats['section']))
        @if($stats['section'] === 'stats')
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if(isset($stats['active_employees']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Active Employees</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_employees'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['total_departments']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Total Departments</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_departments'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['total_positions']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Total Positions</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_positions'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['approved_leaves']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Approved Leaves (This Month)</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['approved_leaves'] }}</p>
                    </div>
                    @endif
                    @if(isset($stats['expiring_documents']))
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-600">Expiring Documents (30 days)</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $stats['expiring_documents'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        @elseif($stats['section'] === 'pending')
            <!-- Pending Actions -->
            <div class="space-y-6">
                @if(isset($stats['pending_leave_requests']) && $stats['pending_leave_requests']->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Leave Requests</h3>
                        <a href="{{ route('leaves.pending') }}" class="text-sm text-blue-600 hover:text-blue-900">View All</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($stats['pending_leave_requests'] as $leave)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $leave->employee->user->first_name }} {{ $leave->employee->user->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $leave->leaveType->name ?? 'N/A' }} - {{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d') }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($stats['late_arrivals_today']) && $stats['late_arrivals_today'] > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Late Arrivals Today</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['late_arrivals_today'] }}</p>
                    <a href="{{ route('attendance.records.late') }}" class="text-sm text-blue-600 hover:text-blue-900 mt-2 inline-block">View Details</a>
                </div>
                @endif

                @if(isset($stats['unverified_documents']) && $stats['unverified_documents'] > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Unverified Documents</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['unverified_documents'] }}</p>
                    <a href="{{ route('documents.index') }}?status=pending" class="text-sm text-blue-600 hover:text-blue-900 mt-2 inline-block">View Documents</a>
                </div>
                @endif
            </div>
        @else
            <!-- HR Overview (Default) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(isset($stats['total_employees']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Employees</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_employees'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($stats['pending_leaves']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Pending Leaves</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_leaves'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($stats['today_attendance']))
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Today's Attendance</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['today_attendance'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Dashboard Navigation Tabs -->
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex space-x-4 border-b border-gray-200">
                    <a href="{{ route('dashboard', ['section' => 'overview']) }}" class="px-4 py-2 text-sm font-medium {{ ($stats['section'] ?? 'overview') === 'overview' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        HR Overview
                    </a>
                    <a href="{{ route('dashboard', ['section' => 'stats']) }}" class="px-4 py-2 text-sm font-medium {{ ($stats['section'] ?? 'overview') === 'stats' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        Quick Stats
                    </a>
                    <a href="{{ route('dashboard', ['section' => 'pending']) }}" class="px-4 py-2 text-sm font-medium {{ ($stats['section'] ?? 'overview') === 'pending' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }}">
                        Pending Actions
                    </a>
                </div>
            </div>
        @endif
    @else
        <!-- Stats Grid for other roles -->
        @if(isset($stats) && count($stats) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($stats as $key => $value)
                @if($key !== 'section')
                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">{{ str_replace('_', ' ', $key) }}</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $value }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        @endif
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @if(auth()->user()->hasRole('employee'))
                <a href="{{ route('leaves.create') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Request Leave</span>
                </a>
                <a href="{{ route('attendance.clock-in') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Clock In</span>
                </a>
            @endif
            
            @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('hr_manager') || auth()->user()->hasRole('company_admin'))
                <a href="{{ route('leaves.pending') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Pending Approvals</span>
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium text-gray-700">My Profile</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Welcome to HRMS</p>
                    <p class="text-xs text-gray-500">Get started by exploring the dashboard</p>
                </div>
                <span class="text-xs text-gray-400">Just now</span>
            </div>
        </div>
    </div>
</div>
@endsection
