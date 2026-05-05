@php
    use Illuminate\Support\Facades\Storage;
    $company = config('app.current_company_model');
    $primaryColor = $company?->primary_color ?? '#3B82F6';
    $secondaryColor = $company?->secondary_color ?? '#8B5CF6';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $company ? ($company->name . ' - Register') : 'Register' }} - {{ config('app.name', 'HRMS') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
        }
        
        .btn-primary {
            background-color: {{ $primaryColor }};
        }
        
        .btn-primary:hover {
            background-color: {{ $primaryColor }};
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .text-primary {
            color: {{ $primaryColor }};
        }
        
        .border-primary {
            border-color: {{ $primaryColor }};
        }
        
        .focus-ring-primary:focus {
            ring-color: {{ $primaryColor }};
            border-color: {{ $primaryColor }};
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding & Image -->
        <div class="hidden lg:flex lg:w-1/2 gradient-bg relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            
            <div class="relative z-10 flex flex-col justify-between p-12 text-white">
                <div>
                    @if($company && $company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="h-12 w-auto mb-8">
                    @else
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold">{{ $company?->name ?? 'HRMS' }}</span>
                        </div>
                    @endif
                    
                    @if($company && $company->registration_page_image)
                        <div class="mb-8">
                            <img src="{{ Storage::url($company->registration_page_image) }}" 
                                 alt="{{ $company->name }}" 
                                 class="rounded-2xl shadow-2xl w-full max-w-md">
                        </div>
                    @endif
                    
                    @if($company && ($company->registration_page_title || $company->registration_page_subtitle))
                        <div class="space-y-4">
                            @if($company->registration_page_title)
                                <h1 class="text-4xl font-bold leading-tight">{{ $company->registration_page_title }}</h1>
                            @else
                                <h1 class="text-4xl font-bold leading-tight">Create Your Account</h1>
                            @endif
                            
                            @if($company->registration_page_subtitle)
                                <p class="text-xl text-white/90 leading-relaxed">{{ $company->registration_page_subtitle }}</p>
                            @else
                                <p class="text-xl text-white/90 leading-relaxed">Join us today and start managing your workforce efficiently with our comprehensive HRMS platform.</p>
                            @endif
                        </div>
                    @else
                        <div class="space-y-4">
                            <h1 class="text-4xl font-bold leading-tight">Create Your Account</h1>
                            <p class="text-xl text-white/90 leading-relaxed">Join us today and start managing your workforce efficiently with our comprehensive HRMS platform.</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-8">
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2 text-white/80 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Free to get started</span>
                        </div>
                        <div class="flex items-center space-x-2 text-white/80 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>Secure & Encrypted</span>
                        </div>
                        <div class="flex items-center space-x-2 text-white/80 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Quick setup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 overflow-y-auto">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-8 text-center">
                    @if($company && $company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="h-12 w-auto mx-auto mb-4">
                    @endif
                    <h1 class="text-3xl font-bold text-gray-900">Create Account</h1>
                    <p class="text-gray-600 mt-2">Sign up to get started</p>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-red-800 font-semibold">Please correct the following errors:</span>
                        </div>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
                    <!-- Name Fields -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                First Name
                            </label>
                            <input id="first_name" 
                                   type="text" 
                                   name="first_name" 
                                   value="{{ old('first_name') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="given-name"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="John">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Last Name
                            </label>
                            <input id="last_name" 
                                   type="text" 
                                   name="last_name" 
                                   value="{{ old('last_name') }}" 
                                   required 
                                   autocomplete="family-name"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="Doe">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="username"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="••••••••">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input id="password_confirmation" 
                                   type="password" 
                                   name="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                   placeholder="••••••••">
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="w-full btn-primary text-white font-semibold py-3 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 mt-6">
                        Create Account
                    </button>
                    
                    <!-- Login Link -->
                    <div class="text-center pt-4">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">
                                Sign in
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
