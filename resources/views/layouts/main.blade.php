<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HRMS') }} - @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex" 
         x-data="{ 
             sidebarOpen: false,
             init() {
                 // On desktop (lg), sidebar should be open by default
                 if (window.innerWidth >= 1024) {
                     this.sidebarOpen = true;
                 }
                 // Handle window resize
                 const handleResize = () => {
                     if (window.innerWidth >= 1024) {
                         this.sidebarOpen = true;
                     } else {
                         this.sidebarOpen = false;
                     }
                 };
                 window.addEventListener('resize', handleResize);
                 // Cleanup on component destroy
                 this.$el.addEventListener('alpine:destroy', () => {
                     window.removeEventListener('resize', handleResize);
                 });
             }
         }">
        <!-- Sidebar -->
        @auth
            @include('components.sidebar')
        @endauth

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
            <!-- Top Navigation -->
            @auth
                @include('components.top-nav')
            @endauth

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 lg:hidden"
         @click="sidebarOpen = false"
         style="display: none;">
    </div>
    
    @stack('scripts')
</body>
</html>
