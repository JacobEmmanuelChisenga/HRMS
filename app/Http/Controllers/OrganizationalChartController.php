<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeProfile;
use App\Models\User;

class OrganizationalChartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        // Get all employees with their managers
        $employees = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
            $q->where('company_id', $companyId)
              ->where('status', 'active');
        })
        ->with(['user', 'user.role', 'department', 'position', 'manager', 'manager.role'])
        ->get();

        // Build organizational hierarchy (handle managers that are not employees, e.g., Company Admins)
        $hierarchy = $this->buildHierarchy($employees);

        return view('organizational-chart.index', compact('hierarchy', 'employees'));
    }

    private function buildHierarchy($employees)
    {
        // Managers present in employee profiles (have employee records)
        $employeeUserIds = $employees->pluck('user_id')->toArray();

        // Root nodes: no manager OR manager is not an employee profile (e.g., Company Admin without profile)
        $rootEmployees = $employees->filter(function ($emp) use ($employeeUserIds) {
            return is_null($emp->manager_id) || !in_array($emp->manager_id, $employeeUserIds);
        });

        // Build tree structure
        $buildTree = function ($parentUserId = null) use (&$buildTree, $employees) {
            return $employees->filter(function ($emp) use ($parentUserId) {
                return $emp->manager_id === $parentUserId;
            })->map(function ($emp) use (&$buildTree, $employees) {
                return [
                    'employee' => $emp,
                    'children' => $buildTree($emp->user_id)
                ];
            });
        };

        // Attach children to roots
        return $rootEmployees->map(function ($emp) use ($buildTree) {
            return [
                'employee' => $emp,
                'children' => $buildTree($emp->user_id)
            ];
        });
    }
}
