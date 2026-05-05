@extends('layouts.main')

@section('title', 'General Settings')
@section('page-title', 'General Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">General Settings</h2>
        <form action="{{ route('system-settings.general.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700">Application Name</label>
                    <input type="text" name="app_name" id="app_name" value="{{ config('app.name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="app_url" class="block text-sm font-medium text-gray-700">Application URL</label>
                    <input type="url" name="app_url" id="app_url" value="{{ config('app.url') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                    <input type="text" name="timezone" id="timezone" value="{{ config('app.timezone') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
