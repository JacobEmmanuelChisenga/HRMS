@extends('layouts.main')

@section('title', 'Company Details')
@section('page-title', 'Company Details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center space-x-4">
                @if($company->logo)
                    <img class="h-16 w-16 rounded-lg object-cover" src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}">
                @else
                    <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($company->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h2>
                    <p class="text-gray-600">{{ $company->email }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('super-admin.companies.landing-page', $company->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Edit Landing Page
                </a>
                <a href="{{ route('super-admin.companies.auth-pages', $company->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                    Edit Auth Pages
                </a>
                <a href="{{ route('companies.edit', $company) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Edit Company
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Company Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subdomain</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->subdomain ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->phone ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->address ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">City</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->city ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Country</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->country ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Subscription & Status</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($company->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @elseif($company->status === 'inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subscription Plan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->subscription_plan ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Subscription Expires</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->subscription_expires_at ? $company->subscription_expires_at->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Total Users</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->users->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $company->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
