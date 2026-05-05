@extends('layouts.main')

@section('title', 'Scheduled Announcements')
@section('page-title', 'Scheduled Announcements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Scheduled Announcements</h2>
            <p class="text-gray-600 mt-1">Announcements scheduled for future publication</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('announcements.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Create Announcement
            </a>
            <a href="{{ route('announcements.all') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                All Announcements
            </a>
        </div>
    </div>

    <!-- Announcements Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posted By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publish Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days Until</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($announcements as $announcement)
                    @php
                        $daysUntil = Carbon\Carbon::parse($announcement->publish_date)->diffInDays(Carbon\Carbon::today());
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $announcement->title }}</div>
                            <div class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($announcement->content, 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $announcement->postedBy->first_name }} {{ $announcement->postedBy->last_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ Carbon\Carbon::parse($announcement->publish_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $daysUntil }} {{ $daysUntil == 1 ? 'day' : 'days' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $announcement->expiry_date ? Carbon\Carbon::parse($announcement->expiry_date)->format('M d, Y') : 'No expiry' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('announcements.show', $announcement->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                            <a href="{{ route('announcements.edit', $announcement->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No scheduled announcements found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
            {{ $announcements->links() }}
        </div>
    </div>
</div>
@endsection
