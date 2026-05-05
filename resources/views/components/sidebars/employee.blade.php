<!-- Employee Sidebar -->
<div x-data="{ open: { leaves: true } }">
    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span class="font-medium">Dashboard</span>
    </a>

    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span class="font-medium">My Profile</span>
    </a>

    <!-- My Leave -->
    <div class="space-y-2 mt-2">
        <button @click="open.leaves = !open.leaves" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.*') || request()->routeIs('holidays.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">My Leave</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open.leaves }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="open.leaves" x-transition class="ml-8 space-y-1">
            <div class="px-2 py-1 text-xs text-gray-500">Accrual: 2 leave days / month</div>

            <a href="{{ route('leaves.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Request Leave</span>
            </a>

            <div class="ml-2 mt-1 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2">My Leave Requests</p>
                <a href="{{ route('leaves.my-leaves') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.my-leaves') && !request()->get('status') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">All / History</span>
                </a>
                <a href="{{ route('leaves.my-leaves', ['status' => 'pending']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.my-leaves') && request()->get('status') === 'pending' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Pending</span>
                </a>
                <a href="{{ route('leaves.my-leaves', ['status' => 'approved']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.my-leaves') && request()->get('status') === 'approved' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Approved</span>
                </a>
                <a href="{{ route('leaves.my-leaves', ['status' => 'rejected']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.my-leaves') && request()->get('status') === 'rejected' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Rejected</span>
                </a>
            </div>

            <a href="{{ route('leaves.my-leaves', ['view' => 'balance']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.my-leaves') && request()->get('view') === 'balance' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Leave Balance</span>
            </a>

            <a href="{{ route('leaves.my-leaves', ['view' => 'calendar']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('leaves.my-leaves') && request()->get('view') === 'calendar' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Leave Calendar</span>
            </a>

            <a href="{{ route('holidays.calendar') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('holidays.calendar') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Company Holidays</span>
            </a>
        </div>
    </div>

    <!-- My Attendance -->
    <div class="space-y-2 mt-2">
        <button @click="open.attendance = !open.attendance" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">My Attendance</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open.attendance }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="open.attendance" x-transition class="ml-8 space-y-1">
            <a href="{{ route('attendance.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Clock In / Out</span>
            </a>
            <a href="{{ route('attendance.qr-code') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.qr-code') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1 h-1 rounded-full bg-current"></span>
                <span class="text-sm">Scan QR Code</span>
            </a>
            <a href="{{ route('attendance.index', ['mode' => 'manual']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.index') && request()->get('mode') === 'manual' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1 h-1 rounded-full bg-current"></span>
                <span class="text-sm">Manual Check-in</span>
            </a>

            <div class="ml-2 mt-1 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2">My Attendance Records</p>
                <a href="{{ route('attendance.my-attendance', ['range' => 'today']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && request()->get('range') === 'today' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Today</span>
                </a>
                <a href="{{ route('attendance.my-attendance', ['range' => 'week']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && request()->get('range') === 'week' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">This Week</span>
                </a>
                <a href="{{ route('attendance.my-attendance', ['range' => 'month']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && request()->get('range') === 'month' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">This Month</span>
                </a>
                <a href="{{ route('attendance.my-attendance') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && !request()->get('range') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">History</span>
                </a>
            </div>

            <div class="ml-2 mt-1 space-y-1 border-l border-gray-200 pl-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2">Attendance Summary</p>
                <a href="{{ route('attendance.my-attendance', ['summary' => 'hours']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && request()->get('summary') === 'hours' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Hours Worked</span>
                </a>
                <a href="{{ route('attendance.my-attendance', ['summary' => 'late']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && request()->get('summary') === 'late' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Late Days</span>
                </a>
                <a href="{{ route('attendance.my-attendance', ['summary' => 'absences']) }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('attendance.my-attendance') && request()->get('summary') === 'absences' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                    <span class="w-1 h-1 rounded-full bg-current"></span>
                    <span class="text-sm">Absences</span>
                </a>
            </div>
        </div>
    </div>

    <a href="{{ route('documents.my-documents') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('documents.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span class="font-medium">My Documents</span>
    </a>

    <a href="{{ route('announcements.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('announcements.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
        </svg>
        <span class="font-medium">Announcements</span>
    </a>
</div>
