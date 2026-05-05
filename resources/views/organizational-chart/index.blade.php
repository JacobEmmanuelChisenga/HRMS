@extends('layouts.main')

@section('title', 'Organizational Chart')
@section('page-title', 'Organizational Chart')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Organizational Chart</h2>
            <p class="text-gray-600 mt-1">View your company's organizational hierarchy</p>
        </div>
    </div>

    <!-- Organizational Chart -->
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <div class="min-w-full" id="org-chart">
            @if($hierarchy->count() > 0)
                <div class="flex flex-col items-center">
                    @foreach($hierarchy as $item)
                        @include('organizational-chart.partials.node', ['item' => $item, 'level' => 0])
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No organizational structure available. Add employees and assign managers to build your chart.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.org-node {
    margin: 20px;
    display: inline-block;
    vertical-align: top;
}

.org-children {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    position: relative;
}

.org-children::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    width: 2px;
    height: 20px;
    background: #e5e7eb;
    transform: translateX(-50%);
}

.org-node-wrapper {
    position: relative;
}

.org-node-wrapper::before {
    content: '';
    position: absolute;
    top: -20px;
    left: 50%;
    width: 2px;
    height: 20px;
    background: #e5e7eb;
    transform: translateX(-50%);
}

.org-node-wrapper:not(:only-child)::after {
    content: '';
    position: absolute;
    top: -20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e5e7eb;
}
</style>
@endsection
