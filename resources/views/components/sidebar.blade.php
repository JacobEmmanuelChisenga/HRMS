@php
    $user = auth()->user();
    $roleSlug = $user->role ? $user->role->slug : 'employee';
@endphp

<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out"
       :class="{ 
           '-translate-x-full lg:translate-x-0': !sidebarOpen, 
           'translate-x-0': sidebarOpen 
       }"
       x-show="true">
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-800">HRMS</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            @if(view()->exists("components.sidebars.{$roleSlug}"))
                @include("components.sidebars.{$roleSlug}")
            @else
                @include('components.sidebars.employee')
            @endif
        </nav>

        <!-- User Info -->
        <div class="border-t border-gray-200 p-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'S', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $user->role ? $user->role->name : 'Employee' }}</p>
                </div>
            </div>
        </div>
    </div>
</aside>
