@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">My Profile</h2>
        <p class="text-gray-600 mt-1">Manage your profile settings and preferences</p>
    </div>

    <!-- Profile Categories -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Settings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Settings</h3>
            <p class="text-sm text-gray-600 mb-4">Update your personal information and profile photo</p>
            <a href="{{ route('profile.settings') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Edit Profile
            </a>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
            <p class="text-sm text-gray-600 mb-4">Update your account password for better security</p>
            <a href="{{ route('profile.change-password') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Change Password
            </a>
        </div>

        <!-- Logout -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Logout</h3>
            <p class="text-sm text-gray-600 mb-4">Sign out of your account</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-block px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center space-x-4">
                @if($user->profile_photo)
                <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile Photo" class="h-24 w-24 rounded-full object-cover">
                @else
                <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                @endif
                <div>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    @if($user->phone)
                    <p class="text-sm text-gray-600">{{ $user->phone }}</p>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1"><strong>Role:</strong> {{ $user->role->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mb-1"><strong>Company:</strong> {{ $user->company->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 mb-1"><strong>Status:</strong> 
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
