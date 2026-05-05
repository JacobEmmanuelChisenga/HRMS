@extends('layouts.main')

@section('title', 'Edit Landing Page')
@section('page-title', 'Edit Landing Page')

@section('content')
<div class="max-w-4xl mx-auto">
    @if(auth()->user()->hasRole('super_admin'))
        <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800">
                <strong>Editing landing page for:</strong> {{ $company->name }} ({{ $company->subdomain }})
            </p>
        </div>
    @endif
    
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ auth()->user()->hasRole('super_admin') ? route('super-admin.companies.landing-page.update', $company->id) : route('settings.landing-page.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Enable/Disable Landing Page -->
            <div class="flex items-center">
                <input type="checkbox" name="landing_page_enabled" id="landing_page_enabled" value="1" 
                    {{ $company->landing_page_enabled ? 'checked' : '' }}
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="landing_page_enabled" class="ml-2 text-sm font-medium text-gray-700">
                    Enable Landing Page
                </label>
            </div>
            <p class="text-sm text-gray-500">When enabled, visitors to your subdomain will see this landing page instead of being redirected to login.</p>

            <hr>

            <!-- Title -->
            <div>
                <label for="landing_page_title" class="block text-sm font-medium text-gray-700 mb-1">
                    Landing Page Title
                </label>
                <input type="text" name="landing_page_title" id="landing_page_title" 
                    value="{{ old('landing_page_title', $company->landing_page_title) }}"
                    placeholder="Welcome to {{ $company->name }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('landing_page_title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div>
                <label for="landing_page_content" class="block text-sm font-medium text-gray-700 mb-1">
                    Landing Page Content
                </label>
                <textarea name="landing_page_content" id="landing_page_content" rows="6"
                    placeholder="Enter your company description, welcome message, or any information you want visitors to see..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('landing_page_content', $company->landing_page_content) }}</textarea>
                @error('landing_page_content')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image -->
            <div>
                <label for="landing_page_image" class="block text-sm font-medium text-gray-700 mb-1">
                    Landing Page Image
                </label>
                @if($company->landing_page_image)
                    <div class="mb-3">
                        <img src="{{ Storage::url($company->landing_page_image) }}" alt="Current image" class="max-w-md rounded-lg shadow">
                    </div>
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="remove_landing_page_image" id="remove_landing_page_image" value="1"
                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="remove_landing_page_image" class="ml-2 text-sm text-gray-700">
                            Remove current image
                        </label>
                    </div>
                @endif
                <input type="file" name="landing_page_image" id="landing_page_image" 
                    accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">Recommended: 1200x600px or larger. Max 5MB.</p>
                @error('landing_page_image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <hr>

            <!-- Primary CTA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="landing_page_primary_cta_text" class="block text-sm font-medium text-gray-700 mb-1">
                        Primary Button Text
                    </label>
                    <input type="text" name="landing_page_primary_cta_text" id="landing_page_primary_cta_text" 
                        value="{{ old('landing_page_primary_cta_text', $company->landing_page_primary_cta_text) }}"
                        placeholder="Get Started"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('landing_page_primary_cta_text')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="landing_page_primary_cta_link" class="block text-sm font-medium text-gray-700 mb-1">
                        Primary Button Link
                    </label>
                    <input type="text" name="landing_page_primary_cta_link" id="landing_page_primary_cta_link" 
                        value="{{ old('landing_page_primary_cta_link', $company->landing_page_primary_cta_link) }}"
                        placeholder="/login or https://example.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('landing_page_primary_cta_link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Secondary CTA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="landing_page_secondary_cta_text" class="block text-sm font-medium text-gray-700 mb-1">
                        Secondary Button Text
                    </label>
                    <input type="text" name="landing_page_secondary_cta_text" id="landing_page_secondary_cta_text" 
                        value="{{ old('landing_page_secondary_cta_text', $company->landing_page_secondary_cta_text) }}"
                        placeholder="Learn More"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('landing_page_secondary_cta_text')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="landing_page_secondary_cta_link" class="block text-sm font-medium text-gray-700 mb-1">
                        Secondary Button Link
                    </label>
                    <input type="text" name="landing_page_secondary_cta_link" id="landing_page_secondary_cta_link" 
                        value="{{ old('landing_page_secondary_cta_link', $company->landing_page_secondary_cta_link) }}"
                        placeholder="/about or https://example.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('landing_page_secondary_cta_link')
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
