@php
    use Illuminate\Support\Facades\Storage;
    $company = config('app.current_company_model');
    $primaryColor = $company?->primary_color ?? '#3B82F6';
    $bgColor = $company?->secondary_color ?? '#F3F4F6';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $company ? ($company->name . ' - ' . config('app.name', 'HRMS')) : config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @if($company && $primaryColor)
            <style>
                :root {
                    --company-primary: {{ $primaryColor }};
                    --company-secondary: {{ $company->secondary_color ?? $primaryColor }};
                }
                .btn-primary {
                    background-color: {{ $primaryColor }};
                }
                .btn-primary:hover {
                    background-color: {{ $primaryColor }};
                    opacity: 0.9;
                }
                .text-primary {
                    color: {{ $primaryColor }};
                }
                .border-primary {
                    border-color: {{ $primaryColor }};
                }
            </style>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-color: {{ $bgColor }};">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="flex items-center justify-center">
                    @if($company && $company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="h-24 w-auto max-w-xs object-contain">
                    @else
                        <x-application-logo class="w-24 h-24 fill-current text-gray-500" />
                    @endif
                </a>
                @if($company && $company->name)
                    <h1 class="text-center mt-3 text-xl font-semibold text-gray-800">{{ $company->name }}</h1>
                @endif
            </div>

            <div class="w-full sm:max-w-md mt-2 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
