<!-- Company Admin Sidebar -->
<div x-data="{ 
    openSections: {
        dashboard: true,
        employees: false,
        leaveManagement: false,
        attendance: false,
        documents: false,
        announcements: false,
        reports: false,
        settings: false,
        audit: false,
        profile: false,
        notifications: false
    },
    toggle(section) {
        this.openSections[section] = !this.openSections[section];
    }
}">
    <!-- Dashboard -->
    <div class="space-y-2">
        <button @click="toggle('dashboard')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.dashboard }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.dashboard" x-transition class="ml-8 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') && !request()->get('section') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Company Overview</span>
            </a>
            <a href="{{ route('dashboard') }}?section=stats" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->get('section') == 'stats' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Quick Stats</span>
            </a>
            <a href="{{ route('dashboard') }}?section=activities" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->get('section') == 'activities' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Recent Activities</span>
            </a>
        </div>
    </div>

    <!-- Employees -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('employees')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.*') || request()->routeIs('departments.*') || request()->routeIs('positions.*') || request()->routeIs('organizational-chart.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">Employees</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.employees }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.employees" x-transition class="ml-8 space-y-1">
            <a href="{{ route('employees.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Employees</span>
            </a>
            <a href="{{ route('employees.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Add Employee</span>
            </a>
            <a href="{{ route('employees.directory') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.directory') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Employee Directory</span>
            </a>
            <a href="{{ route('organizational-chart.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('organizational-chart.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Organizational Chart</span>
            </a>
            
            <!-- Departments Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Departments</p>
                <a href="{{ route('departments.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('departments.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">View All Departments</span>
                </a>
                <a href="{{ route('departments.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('departments.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Add Department</span>
                </a>
                <a href="{{ route('departments.heads') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('departments.heads') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Department Heads</span>
                </a>
            </div>

            <!-- Positions Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Positions</p>
                <a href="{{ route('positions.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('positions.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">View All Positions</span>
                </a>
                <a href="{{ route('positions.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('positions.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Add Position</span>
                </a>
            </div>

            <a href="{{ route('employees.terminated') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.terminated') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Terminated Employees</span>
            </a>
        </div>
    </div>

    <!-- Leave Management -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('leaveManagement')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.*') || request()->routeIs('leave-types.*') || request()->routeIs('leave-balances.*') || request()->routeIs('reports.leaves.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">Leave Management</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.leaveManagement }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.leaveManagement" x-transition class="ml-8 space-y-1">
            <!-- Leave Requests -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Requests</p>
                <a href="{{ route('leaves.pending') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.pending') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Pending Approvals</span>
                </a>
                <a href="{{ route('leaves.approved') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.approved') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Approved Leaves</span>
                </a>
                <a href="{{ route('leaves.rejected') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.rejected') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Rejected Leaves</span>
                </a>
                <a href="{{ route('leaves.calendar') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.calendar') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Leave Calendar</span>
                </a>
            </div>

            <!-- Leave Types -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Types</p>
                <a href="{{ route('leave-types.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-types.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">View All Types</span>
                </a>
                <a href="{{ route('leave-types.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-types.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Add Leave Type</span>
                </a>
            </div>

            <!-- Leave Balances -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Balances</p>
                <a href="{{ route('leave-balances.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-balances.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Employee Balances</span>
                </a>
                <a href="{{ route('leave-balances.bulk-adjustments') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-balances.bulk-adjustments') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Bulk Adjustments</span>
                </a>
            </div>

            <!-- Leave Reports -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Reports</p>
                <a href="{{ route('reports.leaves.summary') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.summary') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Summary Report</span>
                </a>
                <a href="{{ route('reports.leaves.detailed') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.detailed') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Detailed Report</span>
                </a>
                <a href="{{ route('reports.leaves.export') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.export') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Export Data</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('attendance')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.*') && !request()->routeIs('attendance.index') && !request()->routeIs('attendance.my-attendance') && !request()->routeIs('attendance.clock-*') && !request()->routeIs('attendance.qr-code') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">Attendance</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.attendance }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.attendance" x-transition class="ml-8 space-y-1">
            <!-- Daily Attendance Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Daily Attendance</p>
                <a href="{{ route('attendance.today') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.today') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Today's Attendance</span>
                </a>
                <a href="{{ route('attendance.mark') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.mark') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Mark Attendance</span>
                </a>
                <a href="{{ route('attendance.dashboard') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Attendance Dashboard</span>
                </a>
            </div>

            <!-- QR Code Management Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">QR Code Management</p>
                <a href="{{ route('attendance.qr.display') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.qr.display') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">QR Code Display</span>
                </a>
                <a href="{{ route('attendance.qr.settings') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.qr.settings') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">QR Settings</span>
                </a>
            </div>

            <!-- Attendance Records Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Attendance Records</p>
                <a href="{{ route('attendance.records.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.records.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">View All Records</span>
                </a>
                <a href="{{ route('attendance.records.late') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.records.late') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Late Arrivals</span>
                </a>
                <a href="{{ route('attendance.records.absences') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.records.absences') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Absences</span>
                </a>
            </div>

            <!-- Attendance Reports Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Attendance Reports</p>
                <a href="{{ route('attendance.reports.daily') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.daily') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Daily Report</span>
                </a>
                <a href="{{ route('attendance.reports.monthly') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.monthly') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Monthly Report</span>
                </a>
                <a href="{{ route('attendance.reports.employee-summary') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.employee-summary') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Employee Summary</span>
                </a>
                <a href="{{ route('attendance.reports.export') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.export') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Export Data</span>
                </a>
            </div>

            <!-- Attendance Settings Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Attendance Settings</p>
                <a href="{{ route('attendance.settings.work-hours') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.settings.work-hours') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Work Hours</span>
                </a>
                <a href="{{ route('attendance.settings.late-thresholds') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.settings.late-thresholds') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Late Thresholds</span>
                </a>
                <a href="{{ route('attendance.settings.geofencing') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.settings.geofencing') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Geofencing</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Documents -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('documents')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.*') && !request()->routeIs('documents.my-documents') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">Documents</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.documents }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.documents" x-transition class="ml-8 space-y-1">
            <a href="{{ route('documents.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Documents</span>
            </a>
            <a href="{{ route('documents.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Upload Document</span>
            </a>

            <!-- Document Categories Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Document Categories</p>
                <a href="{{ route('documents.categories.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.categories.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">View Categories</span>
                </a>
                <a href="{{ route('documents.categories.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.categories.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Add Category</span>
                </a>
            </div>

            <a href="{{ route('documents.expiring') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.expiring') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Expiring Documents</span>
            </a>
            <a href="{{ route('documents.requests') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.requests') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Document Requests</span>
            </a>
            <a href="{{ route('documents.reports') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.reports') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Document Reports</span>
            </a>
        </div>
    </div>

    <!-- Announcements -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('announcements')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.*') && !request()->routeIs('announcements.index') && !request()->routeIs('announcements.show') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <span class="font-medium">Announcements</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.announcements }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.announcements" x-transition class="ml-8 space-y-1">
            <a href="{{ route('announcements.all') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.all') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Announcements</span>
            </a>
            <a href="{{ route('announcements.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Create Announcement</span>
            </a>
            <a href="{{ route('announcements.scheduled') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.scheduled') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Scheduled Posts</span>
            </a>
            <a href="{{ route('announcements.drafts') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.drafts') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Draft Announcements</span>
            </a>
        </div>
    </div>

    <!-- Reports & Analytics -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('reports')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="font-medium">Reports & Analytics</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.reports }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.reports" x-transition class="ml-8 space-y-1">
            <!-- Employee Reports Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Employee Reports</p>
                <a href="{{ route('reports.employees.list') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.employees.list') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Employee List</span>
                </a>
                <a href="{{ route('reports.employees.headcount') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.employees.headcount') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Headcount Analysis</span>
                </a>
                <a href="{{ route('reports.employees.demographics') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.employees.demographics') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Demographics</span>
                </a>
            </div>

            <!-- Attendance Analytics Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Attendance Analytics</p>
                <a href="{{ route('reports.attendance.trends') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.attendance.trends') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Attendance Trends</span>
                </a>
                <a href="{{ route('reports.attendance.department-comparison') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.attendance.department-comparison') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Department Comparison</span>
                </a>
                <a href="{{ route('reports.attendance.time-analysis') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.attendance.time-analysis') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Time Analysis</span>
                </a>
            </div>

            <!-- Leave Analytics Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Analytics</p>
                <a href="{{ route('reports.leaves.utilization') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.utilization') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Leave Utilization</span>
                </a>
                <a href="{{ route('reports.leaves.trends') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.trends') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Leave Trends</span>
                </a>
                <a href="{{ route('reports.leaves.cost-analysis') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.cost-analysis') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Cost Analysis</span>
                </a>
            </div>

            <!-- Document Reports Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Document Reports</p>
                <a href="{{ route('reports.documents.inventory') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.documents.inventory') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Document Inventory</span>
                </a>
                <a href="{{ route('reports.documents.compliance') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.documents.compliance') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Compliance Status</span>
                </a>
            </div>

            <!-- Custom Reports Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Custom Reports</p>
                <a href="{{ route('reports.custom.builder') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.custom.builder') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Report Builder</span>
                </a>
                <a href="{{ route('reports.custom.saved') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.custom.saved') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Saved Reports</span>
                </a>
                <a href="{{ route('reports.custom.scheduled') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.custom.scheduled') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Scheduled Reports</span>
                </a>
            </div>

            <!-- Export Center Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Export Center</p>
                <a href="{{ route('reports.exports.excel') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.exports.excel') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Export to Excel</span>
                </a>
                <a href="{{ route('reports.exports.pdf') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.exports.pdf') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Export to PDF</span>
                </a>
                <a href="{{ route('reports.exports.bulk') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.exports.bulk') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Bulk Exports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Company Settings -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('settings')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.*') || request()->routeIs('holidays.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">Company Settings</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.settings }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.settings" x-transition class="ml-8 space-y-1">
            <!-- Company Profile Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Company Profile</p>
                <a href="{{ route('settings.company.basic-information') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.company.basic-information') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Basic Information</span>
                </a>
                <a href="{{ route('settings.company.branding') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.company.branding') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Branding</span>
                </a>
                <a href="{{ route('settings.landing-page.edit') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.landing-page.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Landing Page</span>
                </a>
                <a href="{{ route('settings.auth-pages.edit') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.auth-pages.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Login & Registration</span>
                </a>
            </div>

            <!-- Holidays Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Holidays</p>
                <a href="{{ route('holidays.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('holidays.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Public Holidays</span>
                </a>
                <a href="{{ route('holidays.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('holidays.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Add Holiday</span>
                </a>
                <a href="{{ route('holidays.calendar') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('holidays.calendar') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Holiday Calendar</span>
                </a>
            </div>

            <!-- User Management Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">User Management</p>
                <a href="{{ route('settings.users.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.users.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">All Users</span>
                </a>
                <a href="{{ route('settings.users.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.users.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Add User</span>
                </a>
                <a href="{{ route('settings.roles.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.roles.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Roles</span>
                </a>
                <a href="{{ route('settings.users.roles-permissions') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.users.roles-permissions') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Roles & Permissions</span>
                </a>
            </div>

            <!-- Subscription Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Subscription</p>
                <a href="{{ route('settings.subscription.current-plan') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.subscription.current-plan') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Current Plan</span>
                </a>
                <a href="{{ route('settings.subscription.billing-history') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.subscription.billing-history') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Billing History</span>
                </a>
                <a href="{{ route('settings.subscription.upgrade-plan') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.subscription.upgrade-plan') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Upgrade Plan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Audit & Compliance -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('audit')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="font-medium">Audit & Compliance</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.audit }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.audit" x-transition class="ml-8 space-y-1">
            <!-- Audit Trails Sub-section -->
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Audit Trails</p>
                <a href="{{ route('audit.activities.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.activities.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">All Activities</span>
                </a>
                <a href="{{ route('audit.activities.user-actions') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.activities.user-actions') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">User Actions</span>
                </a>
                <a href="{{ route('audit.activities.data-changes') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.activities.data-changes') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Data Changes</span>
                </a>
            </div>

            <!-- Document Access Logs -->
            <a href="{{ route('audit.documents.access-logs') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.documents.access-logs') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Document Access Logs</span>
            </a>

            <!-- Attendance Modifications -->
            <a href="{{ route('audit.attendance.modifications') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.attendance.modifications') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Attendance Modifications</span>
            </a>

            <!-- Compliance Reports -->
            <a href="{{ route('audit.compliance.reports') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.compliance.reports') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Compliance Reports</span>
            </a>
        </div>
    </div>

    <!-- My Profile -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        <button @click="toggle('profile')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium">My Profile</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.profile }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.profile" x-transition class="ml-8 space-y-1">
            <a href="{{ route('profile.settings') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('profile.settings') || request()->routeIs('profile.update') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Profile Settings</span>
            </a>
            <a href="{{ route('profile.change-password') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('profile.change-password') || request()->routeIs('profile.update-password') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Change Password</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="px-4 py-2">
                @csrf
                <button type="submit" class="flex items-center space-x-2 w-full text-left text-gray-600 hover:text-red-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
