@extends('layouts.main')

@section('title', 'Document Details')
@section('page-title', 'Document Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Document Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $document->title }}</h2>
                <p class="text-gray-600 mt-1">{{ $document->category->name }}</p>
            </div>
            <a href="{{ route('documents.download', $document->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                <span>Download</span>
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="text-sm text-gray-500">File Name</label>
                <p class="text-gray-900 font-medium">{{ $document->file_name }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">File Size</label>
                <p class="text-gray-900 font-medium">{{ number_format($document->file_size / 1024, 2) }} KB</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">File Type</label>
                <p class="text-gray-900 font-medium">{{ $document->file_type }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Uploaded By</label>
                <p class="text-gray-900 font-medium">{{ $document->uploadedBy->first_name }} {{ $document->uploadedBy->last_name }}</p>
            </div>
            @if($document->employee)
            <div>
                <label class="text-sm text-gray-500">Employee</label>
                <p class="text-gray-900 font-medium">{{ $document->employee->user->first_name }} {{ $document->employee->user->last_name }}</p>
            </div>
            @endif
            <div>
                <label class="text-sm text-gray-500">Upload Date</label>
                <p class="text-gray-900 font-medium">{{ $document->created_at->format('M d, Y h:i A') }}</p>
            </div>
            @if($document->expires_at)
            <div>
                <label class="text-sm text-gray-500">Expires At</label>
                <p class="text-gray-900 font-medium {{ $document->expires_at < now() ? 'text-red-600' : '' }}">
                    {{ $document->expires_at->format('M d, Y') }}
                    @if($document->expires_at < now())
                        <span class="text-xs text-red-600">(Expired)</span>
                    @endif
                </p>
            </div>
            @endif
            <div>
                <label class="text-sm text-gray-500">Status</label>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $document->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $document->is_verified ? 'Verified' : 'Pending Verification' }}
                </span>
            </div>
        </div>

        @if($document->description)
        <div class="border-t pt-4">
            <label class="text-sm text-gray-500">Description</label>
            <p class="text-gray-700 mt-2">{{ $document->description }}</p>
        </div>
        @endif
    </div>

    <!-- Versions -->
    @if($document->versions->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Document Versions</h3>
        <div class="space-y-3">
            @foreach($document->versions as $version)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">Version {{ $version->version_number }}</p>
                    <p class="text-xs text-gray-500">{{ $version->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <a href="#" class="text-blue-600 hover:text-blue-900 text-sm">Download</a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('documents.index') }}" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back to Documents</a>
    </div>
</div>
@endsection
