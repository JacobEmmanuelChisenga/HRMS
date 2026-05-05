@extends('layouts.main')

@section('title', 'Upgrade Plan')
@section('page-title', 'Upgrade Plan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Upgrade Plan</h2>
            <p class="text-gray-600 mt-1">Choose a subscription plan that fits your needs</p>
        </div>
        <a href="{{ route('settings.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Back to Settings
        </a>
    </div>

    <!-- Current Plan -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-blue-800">
            <strong>Current Plan:</strong> {{ ucfirst($company->subscription_plan ?? 'No Plan') }}
        </p>
    </div>

    <!-- Plans -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="bg-white rounded-lg shadow p-6 {{ $company->subscription_plan == $plan ? 'ring-2 ring-blue-500' : '' }}">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ ucfirst($plan) }}</h3>
            <p class="text-gray-600 mb-4">Perfect for {{ $plan }} businesses</p>
            <div class="space-y-2 mb-6">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-sm text-gray-700">All features included</span>
                </div>
            </div>
            @if($company->subscription_plan == $plan)
            <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                Current Plan
            </button>
            @else
            <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Upgrade to {{ ucfirst($plan) }}
            </button>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
