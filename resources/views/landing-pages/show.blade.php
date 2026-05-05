<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $company->landing_page_content ? strip_tags($company->landing_page_content) : 'Welcome to ' . $company->name }}">
    <title>{{ $company->landing_page_title ?? $company->name }} - {{ config('app.name', 'HRMS') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @if($company->primary_color)
        <style>
            :root {
                --company-primary: {{ $company->primary_color }};
                --company-secondary: {{ $company->secondary_color ?? $company->primary_color }};
                --company-accent: {{ $company->accent_color ?? $company->primary_color }};
            }
            
            .gradient-primary {
                background: linear-gradient(135deg, {{ $company->primary_color }} 0%, {{ $company->secondary_color ?? $company->primary_color }} 100%);
            }
            
            .text-primary {
                color: {{ $company->primary_color }};
            }
            
            .bg-primary {
                background-color: {{ $company->primary_color }};
            }
            
            .border-primary {
                border-color: {{ $company->primary_color }};
            }
            
            .hover\:bg-primary:hover {
                background-color: {{ $company->primary_color }};
            }
        </style>
    @endif
    
    <style>
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Fade in animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, {{ $company->primary_color ?? '#3B82F6' }} 0%, {{ $company->secondary_color ?? '#8B5CF6' }} 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-white antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm shadow-sm transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false }" 
         @scroll.window="scrolled = window.scrollY > 20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="h-12 w-auto">
                    @else
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-xl gradient-primary">
                            {{ strtoupper(substr($company->name, 0, 1)) }}
                        </div>
                    @endif
                    <span class="text-xl font-bold text-gray-900 hidden sm:block">{{ $company->name }}</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-primary transition-colors font-medium">Features</a>
                    <a href="#benefits" class="text-gray-700 hover:text-primary transition-colors font-medium">Benefits</a>
                    <a href="#about" class="text-gray-700 hover:text-primary transition-colors font-medium">About</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-primary transition-colors font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary transition-colors font-medium">Login</a>
                        @if(Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2 rounded-lg text-white font-semibold bg-primary hover:opacity-90 transition shadow-lg">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition
             class="md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" @click="mobileMenuOpen = false" class="block text-gray-700 hover:text-primary">Features</a>
                <a href="#benefits" @click="mobileMenuOpen = false" class="block text-gray-700 hover:text-primary">Benefits</a>
                <a href="#about" @click="mobileMenuOpen = false" class="block text-gray-700 hover:text-primary">About</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block text-gray-700 hover:text-primary">Login</a>
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-4 py-2 rounded-lg text-white font-semibold bg-primary text-center">Get Started</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 gradient-primary opacity-5"></div>
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 20% 50%, {{ $company->primary_color ?? '#3B82F6' }}15 0%, transparent 50%), radial-gradient(circle at 80% 80%, {{ $company->secondary_color ?? '#8B5CF6' }}15 0%, transparent 50%);"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="text-center lg:text-left animate-fade-in-up">
                    @if($company->landing_page_title)
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 leading-tight mb-6">
                            {{ $company->landing_page_title }}
                        </h1>
                    @else
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 leading-tight mb-6">
                            Welcome to <span class="gradient-text">{{ $company->name }}</span>
                        </h1>
                    @endif
                    
                    @if($company->landing_page_content)
                        <p class="text-xl md:text-2xl text-gray-600 mb-8 leading-relaxed">
                            {!! nl2br(e($company->landing_page_content)) !!}
                        </p>
                    @else
                        <p class="text-xl md:text-2xl text-gray-600 mb-8 leading-relaxed">
                            Streamline your human resources management with our comprehensive HRMS platform. 
                            Manage employees, track attendance, handle leave requests, and more - all in one place.
                        </p>
                    @endif
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        @if($company->landing_page_primary_cta_text && $company->landing_page_primary_cta_link)
                            <a href="{{ $company->landing_page_primary_cta_link }}" class="px-8 py-4 rounded-lg text-white font-semibold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all bg-primary">
                                {{ $company->landing_page_primary_cta_text }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-4 rounded-lg text-white font-semibold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all bg-primary">
                                Get Started
                            </a>
                        @endif
                        
                        @if($company->landing_page_secondary_cta_text && $company->landing_page_secondary_cta_link)
                            <a href="{{ $company->landing_page_secondary_cta_link }}" class="px-8 py-4 rounded-lg border-2 font-semibold text-lg hover:bg-gray-50 transition border-primary text-primary">
                                {{ $company->landing_page_secondary_cta_text }}
                            </a>
                        @else
                            <a href="#features" class="px-8 py-4 rounded-lg border-2 font-semibold text-lg hover:bg-gray-50 transition border-primary text-primary">
                                Learn More
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Image -->
                <div class="relative animate-fade-in-up" style="animation-delay: 0.2s;">
                    @if($company->landing_page_image)
                        <div class="relative">
                            <img src="{{ Storage::url($company->landing_page_image) }}" 
                                 alt="{{ $company->name }}" 
                                 class="rounded-2xl shadow-2xl w-full h-auto">
                            <div class="absolute -inset-4 bg-gradient-to-r from-primary to-secondary opacity-20 rounded-2xl blur-2xl -z-10"></div>
                        </div>
                    @else
                        <div class="relative bg-gradient-to-br from-primary to-secondary rounded-2xl shadow-2xl p-12 text-white">
                            <div class="space-y-6">
                                <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-bold">HRMS Platform</h3>
                                <p class="text-white/90">Manage your workforce efficiently</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Everything you need to manage your human resources effectively
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="w-14 h-14 rounded-lg gradient-primary flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Employee Management</h3>
                    <p class="text-gray-600">Comprehensive employee profiles, organizational charts, and directory management.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="w-14 h-14 rounded-lg gradient-primary flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Leave Management</h3>
                    <p class="text-gray-600">Streamlined leave requests, approvals, and balance tracking with automatic calculations.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="w-14 h-14 rounded-lg gradient-primary flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Attendance Tracking</h3>
                    <p class="text-gray-600">QR code-based attendance, clock in/out, and comprehensive attendance reports.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="w-14 h-14 rounded-lg gradient-primary flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Document Management</h3>
                    <p class="text-gray-600">Secure document storage, versioning, and access control with expiry tracking.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="w-14 h-14 rounded-lg gradient-primary flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Analytics & Reports</h3>
                    <p class="text-gray-600">Comprehensive reports and analytics to make data-driven HR decisions.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="w-14 h-14 rounded-lg gradient-primary flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Compliant</h3>
                    <p class="text-gray-600">Enterprise-grade security with role-based access control and audit trails.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Why Choose Us?</h2>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Easy to Use</h3>
                                <p class="text-gray-600">Intuitive interface designed for users of all technical levels.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Fully Customizable</h3>
                                <p class="text-gray-600">Brand your platform with your company colors, logo, and branding.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Multi-Tenant Architecture</h3>
                                <p class="text-gray-600">Each company gets their own secure, isolated environment.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">24/7 Support</h3>
                                <p class="text-gray-600">Dedicated support team ready to help you whenever you need.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-2xl p-12">
                        <div class="space-y-8">
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary mb-2">100%</div>
                                <p class="text-gray-700">Cloud-Based</p>
                            </div>
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary mb-2">99.9%</div>
                                <p class="text-gray-700">Uptime Guarantee</p>
                            </div>
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary mb-2">24/7</div>
                                <p class="text-gray-700">Available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="about" class="py-20 gradient-primary">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-white/90 mb-8">
                Join thousands of companies already using our HRMS platform to streamline their HR operations.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 rounded-lg bg-white text-primary font-semibold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                        Go to Dashboard
                    </a>
                @else
                    @if($company->landing_page_primary_cta_text && $company->landing_page_primary_cta_link)
                        <a href="{{ $company->landing_page_primary_cta_link }}" class="px-8 py-4 rounded-lg bg-white text-primary font-semibold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                            {{ $company->landing_page_primary_cta_text }}
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-lg bg-white text-primary font-semibold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
                            Create Account
                        </a>
                    @endif
                    <a href="{{ route('login') }}" class="px-8 py-4 rounded-lg border-2 border-white text-white font-semibold text-lg hover:bg-white/10 transition">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        @if($company->logo)
                            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="h-10 w-auto">
                        @else
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold gradient-primary">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="text-xl font-bold">{{ $company->name }}</span>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Streamline your HR operations with our comprehensive management system.
                    </p>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#features" class="hover:text-white transition">Features</a></li>
                        <li><a href="#benefits" class="hover:text-white transition">Benefits</a></li>
                        <li><a href="#about" class="hover:text-white transition">About</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Resources</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        @if(Route::has('register'))
                            <li><a href="{{ route('register') }}" class="hover:text-white transition">Register</a></li>
                        @endif
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:text-white transition">Dashboard</a></li>
                        @endauth
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        @if($company->email)
                            <li>{{ $company->email }}</li>
                        @endif
                        @if($company->phone)
                            <li>{{ $company->phone }}</li>
                        @endif
                        @if($company->address)
                            <li>{{ $company->address }}</li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} {{ $company->name }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
