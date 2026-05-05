@extends('layouts.main')

@section('title', 'Security Settings')
@section('page-title', 'Security Settings')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Security Settings</h2>
        <p class="text-gray-600 mb-4">Configure security policies and authentication settings.</p>
        <form action="{{ route('system-settings.security.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="password_min_length" class="block text-sm font-medium text-gray-700">Minimum Password Length</label>
                    <input type="number" name="password_min_length" id="password_min_length" value="8" min="6" max="20" required
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
