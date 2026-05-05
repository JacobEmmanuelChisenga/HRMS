@extends('layouts.main')

@section('title', 'Add New Company')
@section('page-title', 'Add New Company')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Company Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Company Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subdomain -->
                <div>
                    <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain *</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required
                            pattern="[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?"
                            class="flex-1 min-w-0 block w-full rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            placeholder="company-name">
                        <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                            .hrms.com
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        This will be the company's login URL: <span class="font-mono text-blue-600" id="subdomain-preview">https://company-name.hrms.com</span>
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        Only lowercase letters, numbers, and hyphens. Must start and end with a letter or number.
                    </p>
                    @error('subdomain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Country -->
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('country')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subscription Plan -->
                <div>
                    <label for="subscription_plan" class="block text-sm font-medium text-gray-700">Subscription Plan</label>
                    <select name="subscription_plan" id="subscription_plan"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">None</option>
                        <option value="basic" {{ old('subscription_plan') == 'basic' ? 'selected' : '' }}>Basic</option>
                        <option value="professional" {{ old('subscription_plan') == 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="enterprise" {{ old('subscription_plan') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    @error('subscription_plan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subscription Expires At -->
                <div>
                    <label for="subscription_expires_at" class="block text-sm font-medium text-gray-700">Subscription Expires At</label>
                    <input type="date" name="subscription_expires_at" id="subscription_expires_at" value="{{ old('subscription_expires_at') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('subscription_expires_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Color Scheme -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                    <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', '#3B82F6') }}"
                        class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary Color</label>
                    <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', '#8B5CF6') }}"
                        class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="accent_color" class="block text-sm font-medium text-gray-700">Accent Color</label>
                    <input type="color" name="accent_color" id="accent_color" value="{{ old('accent_color', '#EC4899') }}"
                        class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <!-- Company Admin User Section -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Admin User</h3>
                <p class="text-sm text-gray-600 mb-4">Create the initial admin user for this company.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Admin First Name -->
                    <div>
                        <label for="admin_first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" name="admin_first_name" id="admin_first_name" value="{{ old('admin_first_name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('admin_first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Last Name -->
                    <div>
                        <label for="admin_last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" name="admin_last_name" id="admin_last_name" value="{{ old('admin_last_name') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('admin_last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Email -->
                    <div>
                        <label for="admin_email" class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('admin_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Password -->
                    <div>
                        <label for="admin_password" class="block text-sm font-medium text-gray-700">Password *</label>
                        <input type="password" name="admin_password" id="admin_password" required minlength="8"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('admin_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    </div>

                    <!-- Admin Password Confirmation -->
                    <div class="md:col-span-2">
                        <label for="admin_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                        <input type="password" name="admin_password_confirmation" id="admin_password_confirmation" required minlength="8"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('companies.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Create Company
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const subdomainInput = document.getElementById('subdomain');
        const preview = document.getElementById('subdomain-preview');
        
        if (subdomainInput && preview) {
            subdomainInput.addEventListener('input', function() {
                const value = this.value.toLowerCase().replace(/[^a-z0-9-]/g, '');
                this.value = value;
                preview.textContent = value ? `https://${value}.hrms.com` : 'https://company-name.hrms.com';
            });
        }
    });
</script>
@endpush
@endsection
