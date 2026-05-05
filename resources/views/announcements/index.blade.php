@extends('layouts.main')

@section('title', 'Announcements')
@section('page-title', 'Announcements')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Announcements</h2>
            <p class="text-gray-600 mt-1">Company announcements and updates</p>
        </div>
        @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
        <a href="{{ route('announcements.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Create Announcement</a>
        @endif
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($announcements as $announcement)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-2">
                <h3 class="text-lg font-semibold text-gray-900">{{ $announcement->title }}</h3>
                <span class="text-xs text-gray-500">{{ $announcement->created_at->format('M d, Y') }}</span>
            </div>
            <p class="text-gray-700 mb-4">{{ \Illuminate\Support\Str::limit($announcement->content, 200) }}</p>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-500">Posted by: {{ $announcement->postedBy->first_name }} {{ $announcement->postedBy->last_name }}</span>
                <a href="{{ route('announcements.show', $announcement->id) }}" class="text-blue-600 hover:text-blue-900 text-sm">Read more →</a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">No announcements available</p>
        </div>
        @endforelse
    </div>
    <div class="bg-gray-50 px-4 py-3 border-t rounded-lg">{{ $announcements->links() }}</div>
</div>
@endsection
