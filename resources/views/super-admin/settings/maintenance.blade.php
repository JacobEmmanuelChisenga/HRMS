@extends('layouts.main')

@section('title', 'Maintenance Mode')
@section('page-title', 'Maintenance Mode')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Maintenance Mode</h2>
        <div class="mb-4">
            <p class="text-gray-600">Current Status: 
                <span class="font-semibold {{ $isMaintenanceMode ? 'text-red-600' : 'text-green-600' }}">
                    {{ $isMaintenanceMode ? 'Enabled' : 'Disabled' }}
                </span>
            </p>
        </div>
        <form action="{{ route('system-settings.maintenance.toggle') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="secret" class="block text-sm font-medium text-gray-700">Maintenance Secret</label>
                    <input type="text" name="secret" id="secret" value="maintenance-secret"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Use this secret to bypass maintenance mode.</p>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 {{ $isMaintenanceMode ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }} text-white rounded-lg">
                        {{ $isMaintenanceMode ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
