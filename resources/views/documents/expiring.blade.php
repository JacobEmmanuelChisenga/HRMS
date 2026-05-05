@extends('layouts.main')

@section('title', 'Expiring Documents')
@section('page-title', 'Expiring Documents')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Expiring Documents</h2>
            <p class="text-gray-600 mt-1">Documents expiring within the next {{ $daysAhead }} days</p>
        </div>
        <div class="flex space-x-3">
            <form method="GET" action="{{ route('documents.expiring') }}" class="flex items-center space-x-3">
                <select name="days" class="border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="7" {{ $daysAhead == 7 ? 'selected' : '' }}>Next 7 days</option>
                    <option value="15" {{ $daysAhead == 15 ? 'selected' : '' }}>Next 15 days</option>
                    <option value="30" {{ $daysAhead == 30 ? 'selected' : '' }}>Next 30 days</option>
                    <option value="60" {{ $daysAhead == 60 ? 'selected' : '' }}>Next 60 days</option>
                    <option value="90" {{ $daysAhead == 90 ? 'selected' : '' }}>Next 90 days</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </form>
            <a href="{{ route('documents.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                All Documents
            </a>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Remaining</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $document)
                    @php
                        $daysRemaining = Carbon\Carbon::parse($document->expires_at)->diffInDays(Carbon\Carbon::today());
                        $isUrgent = $daysRemaining <= 7;
                        $isWarning = $daysRemaining <= 15 && $daysRemaining > 7;
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $isUrgent ? 'bg-red-50' : ($isWarning ? 'bg-yellow-50' : '') }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                            <div class="text-sm text-gray-500">{{ $document->file_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $document->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $document->employee ? $document->employee->user->first_name . ' ' . $document->employee->user->last_name : 'Company Document' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ Carbon\Carbon::parse($document->expires_at)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($isUrgent)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $daysRemaining }} days (Urgent)
                            </span>
                            @elseif($isWarning)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $daysRemaining }} days
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $daysRemaining }} days
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('documents.show', $document->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <a href="{{ route('documents.download', $document->id) }}" class="text-green-600 hover:text-green-900">Download</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No expiring documents found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection
