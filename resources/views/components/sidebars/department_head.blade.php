<!-- Department Head Sidebar -->
<div x-data="{ 
    openSections: {
        dashboard: true,
        employees: false,
        leaveManagement: false,
        attendance: false,
        documents: false,
        announcements: false,
        reports: false,
        audit: false,
        profile: false
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
            <a href="{{ route('dashboard', ['section' => 'overview']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') && (request()->get('section') == 'overview' || !request()->has('section')) ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Executive Overview</span>
            </a>
            <a href="{{ route('dashboard', ['section' => 'stats']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') && request()->get('section') == 'stats' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Quick Stats</span>
            </a>
            <a href="{{ route('dashboard', ['section' => 'pending']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') && request()->get('section') == 'pending' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Pending Actions</span>
            </a>
        </div>
    </div>

    <!-- Employees -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('employees')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
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
                <span class="text-sm">Department Employees</span>
            </a>
            <a href="{{ route('employees.directory') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.directory') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Employee Directory</span>
            </a>
            <a href="{{ route('organizational-chart.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('organizational-chart.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Organizational Chart</span>
            </a>
            <a href="{{ route('employees.terminated') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.terminated') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Terminated Employees</span>
            </a>
        </div>
    </div>

    <!-- Leave Management -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('leaveManagement')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
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
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Types</p>
                <a href="{{ route('leave-types.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-types.index') || request()->routeIs('leave-types.show') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">View All Types</span>
                </a>
            </div>
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Leave Balances</p>
                <a href="{{ route('leave-balances.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-balances.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Employee Balances</span>
                </a>
                <a href="{{ route('leave-balances.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leave-balances.show') || request()->routeIs('leave-balances.adjust') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Adjust Balances</span>
                </a>
            </div>
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
            <a href="{{ route('attendance.qr.display') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.qr.display') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">QR Code Display</span>
            </a>
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
                <a href="{{ route('attendance.records.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.records.edit') || request()->routeIs('attendance.records.update') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Edit Records</span>
                </a>
            </div>
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
        </div>
    </div>

    <!-- Documents -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('documents')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
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
        <button @click="toggle('announcements')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592L6 18V6.5l1.04-1.04a1 1 0 011.414 0L11 5.882zM18 4.24a1.76 1.76 0 01-3.417.592L13 18V6.5l1.04-1.04a1 1 0 011.414 0L18 4.24z"></path>
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

    <!-- Reports -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('reports')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.*') || request()->routeIs('attendance.reports.*') || request()->routeIs('reports.leaves.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="font-medium">Reports</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.reports }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.reports" x-transition class="ml-8 space-y-1">
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
            <a href="{{ route('attendance.reports.daily') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Attendance Reports</span>
            </a>
            <a href="{{ route('reports.leaves.summary') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.leaves.summary') || request()->routeIs('reports.leaves.detailed') || request()->routeIs('reports.leaves.export') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Leave Reports</span>
            </a>
            <a href="{{ route('reports.documents.inventory') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.documents.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Document Reports</span>
            </a>
            <a href="{{ route('reports.exports.excel') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.exports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Export Data</span>
            </a>
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
            <a href="{{ route('audit.documents.access-logs') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.documents.access-logs') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Document Access Logs</span>
            </a>
            <a href="{{ route('audit.attendance.modifications') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.attendance.modifications') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Attendance Modifications</span>
            </a>
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
