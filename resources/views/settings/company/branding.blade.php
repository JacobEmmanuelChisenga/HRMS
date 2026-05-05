@extends('layouts.main')

@section('title', 'Branding')
@section('page-title', 'Branding')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Branding</h2>
            <p class="text-gray-600 mt-1">Customize your company's branding</p>
        </div>
        <a href="{{ route('settings.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Settings
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('settings.company.branding.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Company Logo</label>
                    <div class="flex items-center space-x-4">
                        @if($company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="Company Logo" class="h-20 w-20 object-contain">
                        @endif
                        <div>
                            <input type="file" name="logo" id="logo" accept="image/*"
                                   class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</p>
                        </div>
                    </div>
                    @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color Scheme -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Color Scheme</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Primary Color -->
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $company->primary_color ?? '#3B82F6') }}"
                                       class="h-10 w-20 border-gray-300 rounded">
                                <input type="text" id="primary_color_text" value="{{ old('primary_color', $company->primary_color ?? '#3B82F6') }}"
                                       class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            @error('primary_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Secondary Color -->
                        <div>
                            <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', $company->secondary_color ?? '#10B981') }}"
                                       class="h-10 w-20 border-gray-300 rounded">
                                <input type="text" id="secondary_color_text" value="{{ old('secondary_color', $company->secondary_color ?? '#10B981') }}"
                                       class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            @error('secondary_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Accent Color -->
                        <div>
                            <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="accent_color" id="accent_color" value="{{ old('accent_color', $company->accent_color ?? '#F59E0B') }}"
                                       class="h-10 w-20 border-gray-300 rounded">
                                <input type="text" id="accent_color_text" value="{{ old('accent_color', $company->accent_color ?? '#F59E0B') }}"
                                       class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            @error('accent_color')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Custom Domain -->
                <div>
                    <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-2">Custom Subdomain</label>
                    <div class="flex items-center">
                        <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $company->subdomain) }}"
                               class="flex-1 border-gray-300 rounded-l-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-600">.hrms.local</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to use default domain</p>
                    @error('subdomain')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Sync color picker with text input
    document.getElementById('primary_color').addEventListener('input', function(e) {
        document.getElementById('primary_color_text').value = e.target.value;
    });
    document.getElementById('primary_color_text').addEventListener('input', function(e) {
        document.getElementById('primary_color').value = e.target.value;
    });

    document.getElementById('secondary_color').addEventListener('input', function(e) {
        document.getElementById('secondary_color_text').value = e.target.value;
    });
    document.getElementById('secondary_color_text').addEventListener('input', function(e) {
        document.getElementById('secondary_color').value = e.target.value;
    });

    document.getElementById('accent_color').addEventListener('input', function(e) {
        document.getElementById('accent_color_text').value = e.target.value;
    });
    document.getElementById('accent_color_text').addEventListener('input', function(e) {
        document.getElementById('accent_color').value = e.target.value;
    });
</script>
@endsection
