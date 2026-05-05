@extends('layouts.main')

@section('title', 'QR Code Attendance')
@section('page-title', 'QR Code Attendance')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Scan QR Code to Clock In/Out</h3>
            
            @if($qrCode && $qrCode->is_active && $qrCode->expires_at > now())
            <div class="bg-white p-6 rounded-lg border-2 border-gray-200 inline-block mb-4">
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($qrCode->code) !!}
            </div>
            <div class="space-y-2">
                <p class="text-sm text-gray-600">Code: <span class="font-mono font-semibold">{{ $qrCode->code }}</span></p>
                <p class="text-xs text-gray-500">Expires: {{ $qrCode->expires_at->format('M d, Y h:i A') }}</p>
                <p class="text-xs text-gray-500">QR codes rotate every 5 minutes for security</p>
            </div>
            @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-4">
                <svg class="w-12 h-12 text-yellow-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <p class="text-yellow-800 font-medium">No active QR code available</p>
                <p class="text-yellow-600 text-sm mt-1">Please contact your administrator</p>
            </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('attendance.my-attendance') }}" class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">Back to Attendance</a>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
        <h4 class="font-semibold text-blue-900 mb-2">How to use:</h4>
        <ol class="list-decimal list-inside space-y-1 text-sm text-blue-800">
            <li>Open the HRMS mobile app or use your phone's camera</li>
            <li>Scan the QR code displayed above</li>
            <li>You will be automatically clocked in or out</li>
            <li>The QR code refreshes every 5 minutes for security</li>
        </ol>
    </div>
</div>
@endsection
