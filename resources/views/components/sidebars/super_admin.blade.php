<!-- Super Admin Sidebar -->
<div x-data="{ 
    openSections: {
        dashboard: true,
        companies: false,
        users: false,
        reports: false,
        settings: false,
        notifications: false
    },
    toggle(section) {
        this.openSections[section] = !this.openSections[section];
    }
}">
    <!-- Dashboard -->
    <div class="space-y-2">
        <button @click="toggle('dashboard')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 text-gray-700">
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
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Overview</span>
            </a>
            <a href="{{ route('dashboard') }}?section=companies" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->get('section') == 'companies' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Companies Stats</span>
            </a>
            <a href="{{ route('dashboard') }}?section=revenue" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->get('section') == 'revenue' ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Revenue Analytics</span>
            </a>
        </div>
    </div>

    <!-- Companies Management -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('companies')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('companies.*') || request()->routeIs('subscriptions.*') || request()->routeIs('billing.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="font-medium">Companies Management</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.companies }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.companies" x-transition class="ml-8 space-y-1">
            <a href="{{ route('companies.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('companies.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Companies</span>
            </a>
            <a href="{{ route('companies.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('companies.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Add New Company</span>
            </a>
            <a href="{{ route('companies.settings') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('companies.settings') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Company Settings</span>
            </a>
            <a href="{{ route('subscriptions.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('subscriptions.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Subscription Plans</span>
            </a>
            <a href="{{ route('billing.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('billing.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Billing & Invoices</span>
            </a>
        </div>
    </div>

    <!-- Platform Users -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('users')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('platform-users.*') || request()->routeIs('activity-logs.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="font-medium">Platform Users</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.users }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.users" x-transition class="ml-8 space-y-1">
            <a href="{{ route('platform-users.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('platform-users.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Users</span>
            </a>
            <a href="{{ route('platform-users.super-admins') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('platform-users.super-admins') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Super Admins</span>
            </a>
            <a href="{{ route('activity-logs.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('activity-logs.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Activity Logs</span>
            </a>
        </div>
    </div>

    <!-- System Reports -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('reports')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-reports.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">System Reports</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.reports }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.reports" x-transition class="ml-8 space-y-1">
            <a href="{{ route('system-reports.analytics') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-reports.analytics') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Platform Analytics</span>
            </a>
            <a href="{{ route('system-reports.usage') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-reports.usage') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Usage Statistics</span>
            </a>
            <a href="{{ route('system-reports.performance') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-reports.performance') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Performance Metrics</span>
            </a>
        </div>
    </div>

    <!-- System Settings -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('settings')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-settings.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">System Settings</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.settings }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.settings" x-transition class="ml-8 space-y-1">
            <a href="{{ route('system-settings.general') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-settings.general') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">General Settings</span>
            </a>
            <a href="{{ route('system-settings.email') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-settings.email') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Email Configuration</span>
            </a>
            <a href="{{ route('system-settings.security') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-settings.security') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Security Settings</span>
            </a>
            <a href="{{ route('system-settings.features') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-settings.features') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Feature Toggles</span>
            </a>
            <a href="{{ route('system-settings.maintenance') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('system-settings.maintenance') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">Maintenance Mode</span>
            </a>
        </div>
    </div>

    <!-- Notifications -->
    <div class="space-y-2 mt-2">
        <button @click="toggle('notifications')" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('notifications.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="font-medium">Notifications</span>
            </div>
            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': openSections.notifications }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        <div x-show="openSections.notifications" x-transition class="ml-8 space-y-1">
            <a href="{{ route('notifications.alerts') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('notifications.alerts') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">System Alerts</span>
            </a>
            <a href="{{ route('notifications.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 {{ request()->routeIs('notifications.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-600' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                <span class="text-sm">All Notifications</span>
            </a>
        </div>
    </div>

    <!-- My Profile -->
    <div class="mt-4 pt-4 border-t border-gray-200">
        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-gray-100 {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="font-medium">My Profile</span>
        </a>
    </div>
</div>
