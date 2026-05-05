<?php

namespace App\Traits;

trait ScopesDataByRole
{
    /**
     * Apply scope filtering to employee query based on user role
     */
    protected function scopeEmployees($query)
    {
        $user = auth()->user();
        if (!$user || !$user->role) {
            return $query->whereRaw('1 = 0'); // No access
        }

        $roleSlug = $user->role->slug;
        $companyId = $user->company_id;

        // Base query: always filter by company
        $query->whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        });

        // Apply role-specific scoping
        if ($roleSlug === 'department_head') {
            // Department Head: Only their department
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $query->where('department_id', $employeeProfile->department_id);
            } else {
                // No department assigned, no access
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleSlug === 'team_lead') {
            // Team Lead: Check if they manage any departments
            $managedDepartments = $user->managedDepartments()->pluck('id');
            
            if ($managedDepartments->isNotEmpty()) {
                // If Team Lead manages departments, show all employees in those departments
                $query->whereIn('department_id', $managedDepartments);
            } else {
                // Otherwise, only show direct reports
                $query->where('manager_id', $user->id);
            }
        } elseif (in_array($roleSlug, ['hr_manager', 'company_admin', 'super_admin', 'auditor', 'it_admin'])) {
            // Company-wide: No additional filtering (already filtered by company)
            // All employees in company are visible
        } else {
            // Employee: Only themselves
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    /**
     * Apply scope filtering to leave request query based on user role
     */
    protected function scopeLeaveRequests($query)
    {
        $user = auth()->user();
        if (!$user || !$user->role) {
            return $query->whereRaw('1 = 0');
        }

        $roleSlug = $user->role->slug;
        $companyId = $user->company_id;

        // Base query: filter by company
        $query->whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        });

        // Apply role-specific scoping
        if ($roleSlug === 'department_head') {
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $query->whereHas('employee', function($q) use ($employeeProfile) {
                    $q->where('department_id', $employeeProfile->department_id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleSlug === 'team_lead') {
            // Team Lead: Check if they manage any departments
            $managedDepartments = $user->managedDepartments()->pluck('id');
            
            if ($managedDepartments->isNotEmpty()) {
                // If Team Lead manages departments, show all leave requests in those departments
                $query->whereHas('employee', function($q) use ($managedDepartments) {
                    $q->whereIn('department_id', $managedDepartments);
                });
            } else {
                // Otherwise, only show direct reports' leave requests
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('manager_id', $user->id);
                });
            }
        } elseif (in_array($roleSlug, ['hr_manager', 'company_admin', 'super_admin', 'auditor', 'it_admin'])) {
            // Company-wide: No additional filtering
        } else {
            // Employee: Only their own leaves
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query;
    }

    /**
     * Apply scope filtering to attendance records query based on user role
     */
    protected function scopeAttendanceRecords($query)
    {
        $user = auth()->user();
        if (!$user || !$user->role) {
            return $query->whereRaw('1 = 0');
        }

        $roleSlug = $user->role->slug;
        $companyId = $user->company_id;

        // Base query: filter by company
        $query->whereHas('employee', function($q) use ($companyId) {
            $q->whereHas('user', function($q2) use ($companyId) {
                $q2->where('company_id', $companyId);
            });
        });

        // Apply role-specific scoping
        if ($roleSlug === 'department_head') {
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $query->whereHas('employee', function($q) use ($employeeProfile) {
                    $q->where('department_id', $employeeProfile->department_id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleSlug === 'team_lead') {
            // Team Lead: Check if they manage any departments
            $managedDepartments = $user->managedDepartments()->pluck('id');
            
            if ($managedDepartments->isNotEmpty()) {
                // If Team Lead manages departments, show all attendance records in those departments
                $query->whereHas('employee', function($q) use ($managedDepartments) {
                    $q->whereIn('department_id', $managedDepartments);
                });
            } else {
                // Otherwise, only show direct reports' attendance records
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('manager_id', $user->id);
                });
            }
        } elseif (in_array($roleSlug, ['hr_manager', 'company_admin', 'super_admin', 'auditor', 'it_admin'])) {
            // Company-wide: No additional filtering
        } else {
            // Employee: Only their own attendance
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query;
    }

    /**
     * Apply scope filtering to documents query based on user role
     */
    protected function scopeDocuments($query)
    {
        $user = auth()->user();
        if (!$user || !$user->role) {
            return $query->whereRaw('1 = 0');
        }

        $roleSlug = $user->role->slug;
        $companyId = $user->company_id;

        // Base query: filter by company
        $query->where('company_id', $companyId);

        // Apply role-specific scoping
        if ($roleSlug === 'department_head') {
            $employeeProfile = $user->employeeProfile;
            if ($employeeProfile && $employeeProfile->department_id) {
                $query->whereHas('employee', function($q) use ($employeeProfile) {
                    $q->where('department_id', $employeeProfile->department_id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($roleSlug === 'team_lead') {
            // Team Lead: Check if they manage any departments
            $managedDepartments = $user->managedDepartments()->pluck('id');
            
            if ($managedDepartments->isNotEmpty()) {
                // If Team Lead manages departments, show all documents in those departments
                $query->whereHas('employee', function($q) use ($managedDepartments) {
                    $q->whereIn('department_id', $managedDepartments);
                });
            } else {
                // Otherwise, only show direct reports' documents
                $query->whereHas('employee', function($q) use ($user) {
                    $q->where('manager_id', $user->id);
                });
            }
        } elseif (in_array($roleSlug, ['hr_manager', 'company_admin', 'super_admin', 'auditor', 'it_admin'])) {
            // Company-wide: No additional filtering
        } else {
            // Employee: Only their own documents
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        return $query;
    }
}
