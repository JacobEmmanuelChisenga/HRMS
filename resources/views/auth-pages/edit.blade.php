@php
    use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.main')

@section('title', 'Edit Authentication Pages')
@section('page-title', 'Edit Authentication Pages')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(auth()->user()->hasRole('super_admin'))
        <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <strong>Editing authentication pages for:</strong> {{ $company->name }} ({{ $company->subdomain }})
            </p>
        </div>
    @endif
    
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ auth()->user()->hasRole('super_admin') ? route('super-admin.companies.auth-pages.update', $company->id) : route('settings.auth-pages.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Login Page Section -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Login Page Customization</h3>
                
                <!-- Login Page Title -->
                <div class="mb-4">
                    <label for="login_page_title" class="block text-sm font-medium text-gray-700 mb-1">
                        Login Page Title
                    </label>
                    <input type="text" name="login_page_title" id="login_page_title" 
                        value="{{ old('login_page_title', $company->login_page_title) }}"
                        placeholder="Welcome Back"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('login_page_title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Page Subtitle -->
                <div class="mb-4">
                    <label for="login_page_subtitle" class="block text-sm font-medium text-gray-700 mb-1">
                        Login Page Subtitle
                    </label>
                    <textarea name="login_page_subtitle" id="login_page_subtitle" rows="2"
                        placeholder="Sign in to your account to continue"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('login_page_subtitle', $company->login_page_subtitle) }}</textarea>
                    @error('login_page_subtitle')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Page Image -->
                <div class="mb-4">
                    <label for="login_page_image" class="block text-sm font-medium text-gray-700 mb-1">
                        Login Page Image
                    </label>
                    @if($company->login_page_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($company->login_page_image) }}" alt="Login Page Image" class="h-32 w-auto rounded-lg border border-gray-300">
                        </div>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="remove_login_page_image" id="remove_login_page_image" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="remove_login_page_image" class="ml-2 text-sm text-gray-700">Remove current image</label>
                        </div>
                    @endif
                    <input type="file" name="login_page_image" id="login_page_image" accept="image/jpeg,image/png,image/jpg"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 800x600px. Max file size: 5MB</p>
                    @error('login_page_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Registration Page Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Page Customization</h3>
                
                <!-- Registration Page Title -->
                <div class="mb-4">
                    <label for="registration_page_title" class="block text-sm font-medium text-gray-700 mb-1">
                        Registration Page Title
                    </label>
                    <input type="text" name="registration_page_title" id="registration_page_title" 
                        value="{{ old('registration_page_title', $company->registration_page_title) }}"
                        placeholder="Create Your Account"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('registration_page_title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Registration Page Subtitle -->
                <div class="mb-4">
                    <label for="registration_page_subtitle" class="block text-sm font-medium text-gray-700 mb-1">
                        Registration Page Subtitle
                    </label>
                    <textarea name="registration_page_subtitle" id="registration_page_subtitle" rows="2"
                        placeholder="Join us today and get started"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('registration_page_subtitle', $company->registration_page_subtitle) }}</textarea>
                    @error('registration_page_subtitle')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Registration Page Image -->
                <div class="mb-4">
                    <label for="registration_page_image" class="block text-sm font-medium text-gray-700 mb-1">
                        Registration Page Image
                    </label>
                    @if($company->registration_page_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($company->registration_page_image) }}" alt="Registration Page Image" class="h-32 w-auto rounded-lg border border-gray-300">
                        </div>
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="remove_registration_page_image" id="remove_registration_page_image" value="1" class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="remove_registration_page_image" class="ml-2 text-sm text-gray-700">Remove current image</label>
                        </div>
                    @endif
                    <input type="file" name="registration_page_image" id="registration_page_image" accept="image/jpeg,image/png,image/jpg"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 800x600px. Max file size: 5MB</p>
                    @error('registration_page_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ auth()->user()->hasRole('super_admin') ? route('companies.show', $company->id) : route('settings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
