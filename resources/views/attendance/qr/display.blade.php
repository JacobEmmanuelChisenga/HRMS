@extends('layouts.main')

@section('title', 'QR Code Display')
@section('page-title', 'QR Code Display')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">QR Code Display</h2>
            <p class="text-gray-600 mt-1">Display QR code for employee scanning</p>
        </div>
        @if(auth()->user()->hasRole('company_admin'))
        <div class="flex space-x-3">
            <form action="{{ route('attendance.qr.generate') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Generate New QR Code</span>
                </button>
            </form>
            <a href="{{ route('attendance.qr.settings') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Settings
            </a>
        </div>
        @endif
    </div>

    @if($qrCode && $qrCode->is_active && $qrCode->expires_at > now())
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Scan QR Code to Clock In/Out</h3>
            
            <div class="bg-white p-6 rounded-lg border-2 border-gray-200 inline-block mb-6">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(400)->generate($qrCode->code) !!}
            </div>
            
            <div class="space-y-2 mb-6">
                <p class="text-sm text-gray-600">Code: <span class="font-mono font-semibold">{{ $qrCode->code }}</span></p>
                <p class="text-xs text-gray-500">Expires: {{ $qrCode->expires_at->format('M d, Y h:i A') }}</p>
                <p class="text-xs text-gray-500">Time remaining: {{ $qrCode->expires_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
        <svg class="w-16 h-16 text-yellow-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
        </svg>
        <p class="text-yellow-800 font-medium text-lg mb-2">No active QR code available</p>
        <p class="text-yellow-600 mb-4">
            @if(auth()->user()->hasRole('company_admin'))
                Generate a new QR code to display
            @else
                Contact your administrator to generate a QR code
            @endif
        </p>
        @if(auth()->user()->hasRole('company_admin'))
        <form action="{{ route('attendance.qr.generate') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700">
                Generate QR Code
            </button>
        </form>
        @endif
    </div>
    @endif

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h4 class="font-semibold text-blue-900 mb-3">How to use:</h4>
        <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
            <li>Display this QR code on a screen or print it</li>
            <li>Employees scan the QR code using the HRMS mobile app or phone camera</li>
            <li>They will be automatically clocked in or out</li>
            <li>The QR code automatically refreshes based on your rotation settings</li>
        </ol>
    </div>
</div>
@endsection
