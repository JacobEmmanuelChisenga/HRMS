@extends('layouts.main')

@section('title', $announcement->title)
@section('page-title', $announcement->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h2>
            <p class="text-gray-600 mt-1">
                Posted on {{ $announcement->created_at->format('F d, Y') }}
                @if($announcement->publish_date)
                    • Scheduled for {{ Carbon\Carbon::parse($announcement->publish_date)->format('F d, Y') }}
                @endif
            </p>
        </div>
        <div class="flex space-x-3">
            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
            <a href="{{ route('announcements.edit', $announcement->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit
            </a>
            @endif
            <a href="{{ route('announcements.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back
            </a>
        </div>
    </div>

    <!-- Announcement Content -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="prose max-w-none">
            <div class="whitespace-pre-wrap text-gray-700">{{ $announcement->content }}</div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center text-sm text-gray-500">
                <div>
                    <p><strong>Posted by:</strong> {{ $announcement->postedBy->first_name }} {{ $announcement->postedBy->last_name }}</p>
                    @if($announcement->expiry_date)
                    <p class="mt-1"><strong>Expires on:</strong> {{ Carbon\Carbon::parse($announcement->expiry_date)->format('F d, Y') }}</p>
                    @endif
                </div>
                <div>
                    @if(!$announcement->is_active)
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">Draft</span>
                    @elseif($announcement->publish_date && Carbon\Carbon::parse($announcement->publish_date)->isFuture())
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Scheduled</span>
                    @else
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Published</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
