<!-- IT Admin Sidebar - Technical Control -->
<div x-data="{ 
    openSections: {
        dashboard: true,
        employees: false,
        attendance: false,
        documents: false,
        reports: false,
        system: false,
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="font-medium">System Dashboard</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.dashboard }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.dashboard" x-transition class="ml-8 space-y-1">
            <a href="{{ route('dashboard', ['section' => 'overview']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') && (request()->get('section') == 'overview' || !request()->has('section')) ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Overview</span>
            </a>
            <a href="{{ route('dashboard', ['section' => 'stats']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') && request()->get('section') == 'stats' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Statistics</span>
            </a>
        </div>
    </div>

    <!-- Employees (View Only) -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('employees')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.*') || request()->routeIs('organizational-chart.*') || request()->routeIs('departments.*') || request()->routeIs('positions.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
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
            <a href="{{ route('employees.directory') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('employees.directory') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Employee Directory</span>
            </a>
            <a href="{{ route('organizational-chart.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('organizational-chart.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Organizational Chart</span>
            </a>
            <div class="ml-4 mt-2 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 mt-2">Structure</p>
                <a href="{{ route('departments.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('departments.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Departments</span>
                </a>
                <a href="{{ route('positions.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('positions.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Positions</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Attendance (Technical Management) -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('attendance')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
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
            <a href="{{ route('attendance.today') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.today') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Today's Attendance</span>
            </a>
            <a href="{{ route('attendance.records.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.records.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Records</span>
            </a>
            <a href="{{ route('attendance.qr.display') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.qr.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">QR Code Management</span>
            </a>
            <a href="{{ route('attendance.reports.daily') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Attendance Reports</span>
            </a>
        </div>
    </div>

    <!-- Documents (Technical Management) -->
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
            <a href="{{ route('documents.reports') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.reports') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Document Reports</span>
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('reports')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
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
            <a href="{{ route('reports.employees.list') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.employees.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Employee Reports</span>
            </a>
            <a href="{{ route('attendance.reports.daily') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.reports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Attendance Reports</span>
            </a>
            <a href="{{ route('reports.exports.excel') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('reports.exports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Export Data</span>
            </a>
        </div>
    </div>

    <!-- System Settings -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('system')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">System Settings</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.system }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.system" x-transition class="ml-8 space-y-1">
            <a href="{{ route('settings.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">General Settings</span>
            </a>
            <a href="{{ route('settings.integrations') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.integrations') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Integrations</span>
            </a>
            <a href="{{ route('settings.backups') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.backups') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Backups & Maintenance</span>
            </a>
            <a href="{{ route('settings.logs') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('settings.logs') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Logs</span>
            </a>
        </div>
    </div>

    <!-- Audit & Logs -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('audit')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="font-medium">Audit & Logs</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.audit }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.audit" x-transition class="ml-8 space-y-1">
            <a href="{{ route('audit.activities.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.activities.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Activity Logs</span>
            </a>
            <a href="{{ route('audit.attendance.modifications') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('audit.attendance.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Modifications</span>
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
