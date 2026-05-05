@extends('layouts.main')

@section('title', 'Email Configuration')
@section('page-title', 'Email Configuration')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Email Configuration</h2>
        <form action="{{ route('system-settings.email.update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From Address</label>
                    <input type="email" name="mail_from_address" id="mail_from_address" value="{{ config('mail.from.address') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                    <input type="text" name="mail_from_name" id="mail_from_name" value="{{ config('mail.from.name') }}" required
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
