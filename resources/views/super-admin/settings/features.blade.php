@extends('layouts.main')

@section('title', 'Feature Toggles')
@section('page-title', 'Feature Toggles')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Feature Toggles</h2>
        <form action="{{ route('system-settings.features.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                @foreach($features as $key => $enabled)
                <div class="flex items-center justify-between">
                    <label for="{{ $key }}" class="text-sm font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $key) }}</label>
                    <input type="checkbox" name="{{ $key }}" id="{{ $key }}" {{ $enabled ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </div>
                @endforeach
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
