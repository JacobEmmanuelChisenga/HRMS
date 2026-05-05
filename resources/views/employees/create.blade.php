@extends('layouts.main')

@section('title', 'Add Employee')
@section('page-title', 'Add Employee')

@section('content')
<div class="max-w-4xl mx-auto">
    @php
        $departmentsCount = \App\Models\Department::where('company_id', auth()->user()->company_id)->where('status', 'active')->count();
        $positionsCount = \App\Models\Position::where('company_id', auth()->user()->company_id)->where('status', 'active')->count();
    @endphp
    
    @if($departmentsCount == 0 || $positionsCount == 0)
    <!-- Guided Setup Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium text-yellow-800">
                    Setup Required: Company Structure
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p class="mb-2">Before adding employees, you need to set up your company structure:</p>
                    <ul class="list-disc list-inside space-y-1 mb-3">
                        @if($departmentsCount == 0)
                            <li>No departments found. Create at least one department.</li>
                        @endif
                        @if($positionsCount == 0)
                            <li>No positions found. Create at least one position.</li>
                        @endif
                    </ul>
                    <div class="flex space-x-3">
                        @if($departmentsCount == 0)
                            <a href="{{ route('departments.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Create Department
                            </a>
                        @endif
                        @if($positionsCount == 0)
                            <a href="{{ route('positions.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Create Position
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('first_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('last_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select name="gender" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            <option value="prefer_not_to_say" {{ old('gender') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Province/State</label>
                        <input type="text" name="province" value="{{ old('province') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" value="{{ old('country', 'Zambia') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Identification -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Identification</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">National ID</label>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Passport Number</label>
                        <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Employment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employee Number *</label>
                        <input type="text" name="employee_number" value="{{ old('employee_number') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('employee_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        @php
                            // Use departments from controller, or query if not provided
                            if (!isset($departments)) {
                                $departments = \App\Models\Department::where('company_id', auth()->user()->company_id)
                                    ->orderBy('name')
                                    ->get();
                            }
                        @endphp
                        @if($departments->count() == 0)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            No Departments Found
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p class="mb-2">You need to create at least one department before adding employees.</p>
                                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                                                <a href="{{ route('departments.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                    Create Department
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="flex space-x-2">
                            <select name="department_id" id="department_id" required class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" {{ $departments->count() == 0 ? 'disabled' : '' }}>
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                                <a href="{{ route('departments.create') }}" target="_blank" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm whitespace-nowrap" title="Create New Department">
                                    + New
                                </a>
                            @endif
                        </div>
                        @error('department_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-1">Select a department or create a new one</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                        <div class="flex space-x-2">
                            <select name="position_id" id="position_id" required class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" {{ $departments->count() == 0 ? 'disabled' : '' }}>
                                <option value="">Select Department First</option>
                            </select>
                            @if(auth()->user()->hasRole('company_admin') || auth()->user()->hasRole('hr_manager'))
                                <a href="{{ route('positions.create') }}" target="_blank" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm whitespace-nowrap" title="Create New Position">
                                    + New
                                </a>
                            @endif
                        </div>
                        @error('position_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-1">Positions are filtered by selected department. Create new position if needed.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Manager <span class="text-gray-400 font-normal">(Optional - Assign Later)</span></label>
                        <select name="manager_id" id="manager_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">No Manager (Assign Later)</option>
                            @foreach(\App\Models\User::where('company_id', auth()->user()->company_id)->whereHas('role', function($q) { $q->whereIn('slug', ['team_lead', 'department_head', 'hr_manager', 'company_admin']); })->get() as $manager)
                                <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->first_name }} {{ $manager->last_name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            💡 <strong>Tip:</strong> Manager can be assigned later. If the selected department has a manager, they will be suggested automatically.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date *</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('hire_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Employment Type *</label>
                        <select name="employment_type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="permanent" {{ old('employment_type', 'permanent') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                            <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                            <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        </select>
                        @error('employment_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contract End Date</label>
                        <input type="date" name="contract_end_date" value="{{ old('contract_end_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Required for contract/internship types</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @php
                                $companyId = auth()->user()->company_id;
                                // Get unique roles by slug (prevent duplicates)
                                $roleIds = \App\Models\Role::where('slug', '!=', 'super_admin')
                                    ->where(function($q) use ($companyId) {
                                        $q->whereNull('company_id') // System roles
                                          ->orWhere('company_id', $companyId); // Company roles
                                    })
                                    ->selectRaw('MIN(id) as id')
                                    ->groupBy('slug')
                                    ->pluck('id');
                                $availableRoles = \App\Models\Role::whereIn('id', $roleIds)
                                    ->orderByRaw('company_id IS NULL DESC') // System roles first
                                    ->orderBy('name')
                                    ->get();
                                $defaultRole = \App\Models\Role::where('slug', 'employee')->first();
                            @endphp
                            @foreach($availableRoles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $defaultRole?->id) == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                    @if($role->company_id !== null)
                                        <span class="text-gray-400">(Custom)</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Create custom roles in Settings → Roles</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('employees.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Employee</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // IMMEDIATE TEST - This should show in console immediately
    console.log('🔵 SCRIPT TAG LOADED - JavaScript is running!');
    
    // Store all positions data
    @php
        $companyId = auth()->user()->company_id;
        $positionsData = \App\Models\Position::where('company_id', $companyId)
            ->whereNotNull('department_id')
            ->get()
            ->map(function($p) {
                return [
                    'id' => (int)$p->id, 
                    'title' => $p->title, 
                    'department_id' => (int)$p->department_id
                ];
            });
        $departmentsData = \App\Models\Department::where('company_id', $companyId)->get()->map(function($d) {
            return [
                'id' => (int)$d->id, 
                'manager_id' => $d->manager_id ? (int)$d->manager_id : null
            ];
        });
        
        // Debug: Log what we're loading
        \Log::info('Loading positions for company_id: ' . $companyId);
        \Log::info('Positions count: ' . $positionsData->count());
        \Log::info('Positions data: ' . json_encode($positionsData->toArray()));
    @endphp
    // Ensure data is loaded
    const allPositions = @json($positionsData);
    const allDepartments = @json($departmentsData);
    
    // Immediate console log to verify script is running
    console.log('=== SCRIPT LOADED ===');
    console.log('JavaScript is executing...');
    
    // Wait for DOM to be ready before logging
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            logInitialData();
        });
    } else {
        // DOM already ready
        logInitialData();
    }
    
    function logInitialData() {
        console.log('=== Initial Data Load ===');
        console.log('Loaded positions:', allPositions);
        console.log('Loaded departments:', allDepartments);
        console.log('Total positions:', allPositions.length);
        console.log('Total departments:', allDepartments.length);
        console.log('Positions data type:', typeof allPositions);
        console.log('Is array?', Array.isArray(allPositions));
        
        // Debug: Check if positions have department_id
        if (allPositions && allPositions.length > 0) {
            console.log('Position department_id check:');
            allPositions.forEach((pos, idx) => {
                console.log(`  [${idx}] ${pos.title}: department_id = ${pos.department_id} (${typeof pos.department_id})`);
            });
        } else {
            console.warn('⚠️ No positions loaded! Check if positions exist in database.');
            console.warn('Positions data:', allPositions);
        }
    }
    
    // Filter positions by selected department - rebuild options dynamically
    function filterPositionsByDepartment() {
        const deptSelect = document.getElementById('department_id');
        if (!deptSelect) {
            console.error('Department select element not found');
            return;
        }
        
        const departmentId = deptSelect.value ? parseInt(deptSelect.value, 10) : null;
        const positionSelect = document.getElementById('position_id');
        
        if (!positionSelect) {
            console.error('Position select element not found');
            return;
        }
        
        // Store currently selected value
        const currentValue = positionSelect.value;
        
        // Clear all options except the placeholder
        positionSelect.innerHTML = '';
        
        // Add placeholder option
        const placeholderOption = document.createElement('option');
        placeholderOption.value = '';
        placeholderOption.textContent = departmentId ? 'Select Position' : 'Select Department First';
        placeholderOption.disabled = !departmentId;
        positionSelect.appendChild(placeholderOption);
        
        // Filter and add positions for the selected department
        if (departmentId) {
            console.log('=== Position Filtering Debug ===');
            console.log('Selected Department ID:', departmentId, '(type:', typeof departmentId, ')');
            console.log('All positions array:', allPositions);
            console.log('Total positions available:', allPositions.length);
            
            // Debug: Log each position's department_id
            allPositions.forEach((pos, idx) => {
                console.log(`Position ${idx}: ${pos.title}, department_id: ${pos.department_id} (type: ${typeof pos.department_id})`);
            });
            
            const filteredPositions = allPositions.filter(pos => {
                // Handle null/undefined department_id
                if (pos.department_id === null || pos.department_id === undefined) {
                    console.log(`Position "${pos.title}" has no department_id`);
                    return false;
                }
                
                // Ensure both are numbers for comparison
                const posDeptId = typeof pos.department_id === 'number' 
                    ? pos.department_id 
                    : parseInt(pos.department_id, 10);
                const selectedDeptId = typeof departmentId === 'number' 
                    ? departmentId 
                    : parseInt(departmentId, 10);
                
                const matches = posDeptId === selectedDeptId;
                
                if (!matches) {
                    console.log(`Position "${pos.title}" (dept_id: ${posDeptId}, type: ${typeof posDeptId}) does not match selected dept (${selectedDeptId}, type: ${typeof selectedDeptId})`);
                } else {
                    console.log(`✓ Position "${pos.title}" matches department ${selectedDeptId}`);
                }
                
                return matches;
            });
            
            console.log('Filtered positions count:', filteredPositions.length);
            console.log('Filtered positions:', filteredPositions);
            
            filteredPositions.forEach(position => {
                const option = document.createElement('option');
                option.value = position.id;
                option.textContent = position.title;
                option.setAttribute('data-department-id', position.department_id);
                
                // Restore selection if it matches (for old form values)
                if (currentValue && parseInt(currentValue, 10) === parseInt(position.id, 10)) {
                    option.selected = true;
                }
                
                positionSelect.appendChild(option);
                console.log('Added position option:', position.title);
            });
            
            // If no positions found, show a message
            if (filteredPositions.length === 0) {
                const noPositionsOption = document.createElement('option');
                noPositionsOption.value = '';
                noPositionsOption.textContent = 'No positions available for this department';
                noPositionsOption.disabled = true;
                positionSelect.appendChild(noPositionsOption);
                console.log('No positions found for department', departmentId);
            }
        } else {
            console.log('No department selected - positions hidden');
        }
    }
    
    // Auto-select department manager when department changes
    function autoSelectManager() {
        const deptSelect = document.getElementById('department_id');
        if (!deptSelect) return;
        
        const departmentId = deptSelect.value ? parseInt(deptSelect.value) : null;
        
        if (departmentId) {
            const department = allDepartments.find(d => d.id === departmentId);
            const managerSelect = document.getElementById('manager_id');
            
            if (!managerSelect) return;
            
            // Reset all manager labels
            Array.from(managerSelect.options).forEach(opt => {
                if (opt.value) {
                    opt.textContent = opt.textContent.replace(' (Dept. Manager)', '');
                }
            });
            
            // Auto-select and highlight department manager
            if (department && department.manager_id) {
                const managerOption = managerSelect.querySelector(`option[value="${department.manager_id}"]`);
                if (managerOption) {
                    // Only auto-select if no manager is currently selected (don't override user choice)
                    if (!managerSelect.value || managerSelect.value === '') {
                        managerSelect.value = department.manager_id;
                    }
                    // Highlight it
                    if (!managerOption.textContent.includes('(Dept. Manager)')) {
                        managerOption.textContent += ' (Dept. Manager)';
                    }
                }
            }
        }
    }
    
    // Combined function to handle department change
    function handleDepartmentChange() {
        filterPositionsByDepartment();
        autoSelectManager();
    }
    
    // Attach event listener when DOM is ready
    function initializePositionFiltering() {
        const deptSelect = document.getElementById('department_id');
        const positionSelect = document.getElementById('position_id');
        
        if (!deptSelect) {
            console.error('Department select element not found');
            return;
        }
        
        if (!positionSelect) {
            console.error('Position select element not found');
            return;
        }
        
        console.log('Initializing position filtering...');
        console.log('Department select found:', deptSelect.id);
        console.log('Position select found:', positionSelect.id);
        console.log('Total position options:', positionSelect.options.length);
        
        // Attach change event listener
        deptSelect.addEventListener('change', function(e) {
            console.log('Department changed to:', e.target.value);
            handleDepartmentChange();
        });
        
        // Initialize on page load if department is already selected
        if (deptSelect.value) {
            console.log('Department already selected on load:', deptSelect.value);
            handleDepartmentChange();
        } else {
            // If no department selected, ensure all positions are hidden
            console.log('No department selected, hiding all positions');
            filterPositionsByDepartment();
        }
    }
    
    // Try to initialize immediately (in case DOM is already ready)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePositionFiltering);
    } else {
        // DOM is already ready
        initializePositionFiltering();
    }
    
    // Handle page refresh after creating new department/position
    // Check if we're returning from a create page
    if (sessionStorage.getItem('refreshEmployeeForm') === 'true') {
        sessionStorage.removeItem('refreshEmployeeForm');
        // Reload the page to refresh dropdowns
        window.location.reload();
    }
    
    // Add event listeners to "New" buttons to set refresh flag
    document.querySelectorAll('a[href*="departments.create"], a[href*="positions.create"]').forEach(link => {
        link.addEventListener('click', function() {
            sessionStorage.setItem('refreshEmployeeForm', 'true');
        });
    });
</script>
@endpush
@endsection
