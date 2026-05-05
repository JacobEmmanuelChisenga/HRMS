<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ScopeDataByRole
{
    /**
     * Handle an incoming request.
     * Sets scope configuration based on user role for data filtering
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if ($user && $user->role) {
            $roleSlug = $user->role->slug;
            
            // Set scope based on role
            if ($roleSlug === 'department_head') {
                // Department Head: Filter by their department
                $employeeProfile = $user->employeeProfile;
                if ($employeeProfile && $employeeProfile->department_id) {
                    config(['app.data_scope' => 'department']);
                    config(['app.scope_department_id' => $employeeProfile->department_id]);
                }
            } elseif ($roleSlug === 'team_lead') {
                // Team Lead: Check if they manage any departments
                $managedDepartments = $user->managedDepartments()->pluck('id');
                
                if ($managedDepartments->isNotEmpty()) {
                    // If Team Lead manages departments, use department scope
                    config(['app.data_scope' => 'department']);
                    config(['app.scope_department_ids' => $managedDepartments->toArray()]);
                } else {
                    // Otherwise, filter by direct reports
                    config(['app.data_scope' => 'team']);
                    config(['app.scope_manager_id' => $user->id]);
                }
            } elseif (in_array($roleSlug, ['hr_manager', 'company_admin', 'super_admin', 'auditor', 'it_admin'])) {
                // Company-wide scope
                config(['app.data_scope' => 'company']);
            } else {
                // Employee: Self only
                config(['app.data_scope' => 'self']);
            }
        }
        
        return $next($request);
    }
}
