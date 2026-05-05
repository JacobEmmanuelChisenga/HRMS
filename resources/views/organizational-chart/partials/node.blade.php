@php
    $employee = $item['employee'];
    $children = $item['children'];
@endphp

<div class="org-node-wrapper">
    <div class="org-node bg-white border-2 border-gray-200 rounded-lg p-4 shadow hover:shadow-lg transition-shadow min-w-[200px]">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold text-lg mb-3">
                {{ strtoupper(substr($employee->user->first_name ?? 'U', 0, 1) . substr($employee->user->last_name ?? 'S', 0, 1)) }}
            </div>
            <h3 class="font-semibold text-gray-900 text-sm">{{ $employee->user->first_name }} {{ $employee->user->last_name }}</h3>
            <p class="text-xs text-gray-500 mt-1">
                @if($employee->position)
                    {{ $employee->position->title }}
                @elseif($employee->user->role)
                    {{ $employee->user->role->name }}
                @else
                    N/A
                @endif
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ $employee->department->name ?? 'N/A' }}</p>
            <a href="{{ route('employees.show', $employee->id) }}" class="mt-3 text-xs text-blue-600 hover:text-blue-700">
                View Profile
            </a>
        </div>
    </div>
    
    @if($children->count() > 0)
    <div class="org-children">
        @foreach($children as $child)
            @include('organizational-chart.partials.node', ['item' => $child, 'level' => $level + 1])
        @endforeach
    </div>
    @endif
</div>
