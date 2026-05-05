@extends('layouts.main')

@section('title', 'My Documents')
@section('page-title', 'My Documents')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Documents</h2>
            <p class="text-gray-600 mt-1">View your personal documents</p>
        </div>
        <a href="{{ route('documents.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Upload Document</span>
        </a>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($documents as $document)
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $document->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $document->category->name }}</p>
                </div>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $document->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $document->is_verified ? 'Verified' : 'Pending' }}
                </span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">File:</span>
                    <span class="text-gray-900 font-medium">{{ $document->file_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Size:</span>
                    <span class="text-gray-900">{{ number_format($document->file_size / 1024, 2) }} KB</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Uploaded:</span>
                    <span class="text-gray-900">{{ $document->created_at->format('M d, Y') }}</span>
                </div>
                @if($document->expires_at)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Expires:</span>
                    <span class="text-gray-900 {{ $document->expires_at < now() ? 'text-red-600' : '' }}">
                        {{ $document->expires_at->format('M d, Y') }}
                    </span>
                </div>
                @endif
            </div>

            <div class="flex space-x-2 pt-4 border-t">
                <a href="{{ route('documents.show', $document->id) }}" class="flex-1 text-center bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 text-sm font-medium">View</a>
                <a href="{{ route('documents.download', $document->id) }}" class="flex-1 text-center bg-green-50 text-green-600 px-3 py-2 rounded-lg hover:bg-green-100 text-sm font-medium">Download</a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-lg shadow p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 mb-4">No documents uploaded yet</p>
            <a href="{{ route('documents.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Upload Document</a>
        </div>
        @endforelse
    </div>

    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 rounded-lg">
        {{ $documents->links() }}
    </div>
</div>
@endsection
