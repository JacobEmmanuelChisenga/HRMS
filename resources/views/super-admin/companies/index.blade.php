@extends('layouts.main')

@section('title', 'Companies Management')
@section('page-title', 'All Companies')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    @if(session('login_url'))
                        <div class="mt-2">
                            <p class="text-sm text-green-700">
                                <strong>Company Admin Login URL:</strong>
                                <a href="{{ session('login_url') }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline ml-1">
                                    {{ session('login_url') }}
                                </a>
                            </p>
                            <p class="text-xs text-green-600 mt-1">
                                Email: <strong>{{ session('admin_email') }}</strong>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Companies Management</h2>
            <p class="text-gray-600 mt-1">Manage all companies on the platform</p>
        </div>
        <a href="{{ route('companies.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Add New Company</span>
        </a>
    </div>

    <!-- Companies Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdomain</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($companies as $company)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($company->logo)
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($company->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $company->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $company->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-medium">{{ $company->subdomain ?? 'N/A' }}</div>
                            @if($company->subdomain)
                                @php
                                    $appUrl = config('app.url', 'http://localhost');
                                    $loginUrl = str_replace(['http://', 'https://'], '', $appUrl);
                                    $loginUrl = str_replace('localhost', $company->subdomain . '.localhost', $loginUrl);
                                    $loginUrl = str_replace('127.0.0.1:8000', $company->subdomain . '.127.0.0.1:8000', $loginUrl);
                                    if (!str_contains($appUrl, 'localhost') && !str_contains($appUrl, '127.0.0.1')) {
                                        $domain = parse_url($appUrl, PHP_URL_HOST);
                                        $loginUrl = $company->subdomain . '.' . $domain;
                                    }
                                    $fullLoginUrl = (str_starts_with($appUrl, 'https') ? 'https://' : 'http://') . $loginUrl;
                                @endphp
                                <div class="text-xs text-gray-500 mt-1">
                                    <a href="{{ $fullLoginUrl }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        {{ $fullLoginUrl }}
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $company->users_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $company->subscription_plan ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($company->status === 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @elseif($company->status === 'inactive')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Suspended</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('companies.show', $company) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="{{ route('companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <a href="{{ route('super-admin.companies.landing-page', $company->id) }}" class="text-green-600 hover:text-green-900">Landing</a>
                                <a href="{{ route('super-admin.companies.auth-pages', $company->id) }}" class="text-purple-600 hover:text-purple-900">Auth</a>
                                <form action="{{ route('companies.destroy', $company) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No companies found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($companies->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $companies->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
