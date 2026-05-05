<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeProfile;
use App\Models\LeaveRequest;
use App\Models\AttendanceRecord;
use App\Models\Document;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleSlug = $user->role->slug ?? 'employee';
        
        $data = [
            'user' => $user,
            'role' => $user->role,
        ];

        // Role-specific dashboard data
        switch ($roleSlug) {
            case 'super_admin':
                $data['stats'] = $this->getSuperAdminStats();
                break;
            case 'company_admin':
                $data['stats'] = $this->getCompanyAdminStats($user);
                break;
            case 'hr_manager':
                $data['stats'] = $this->getHrManagerStats($user);
                break;
            case 'manager':
                $data['stats'] = $this->getManagerStats($user);
                break;
            default: // employee
                $data['stats'] = $this->getEmployeeStats($user);
                break;
        }

        return view('dashboard', $data);
    }

    private function getSuperAdminStats()
    {
        $section = request()->get('section', 'overview');
        
        $stats = [
            'total_companies' => \App\Models\Company::count(),
            'total_users' => \App\Models\User::count(),
            'active_companies' => \App\Models\Company::where('status', 'active')->count(),
            'inactive_companies' => \App\Models\Company::where('status', 'inactive')->count(),
            'suspended_companies' => \App\Models\Company::where('status', 'suspended')->count(),
            'section' => $section,
        ];

        if ($section === 'companies') {
            $stats['companies_by_plan'] = \App\Models\Company::select('subscription_plan', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->whereNotNull('subscription_plan')
                ->groupBy('subscription_plan')
                ->get();
            $stats['companies_by_status'] = \App\Models\Company::select('status', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();
            $stats['new_companies_this_month'] = \App\Models\Company::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        }

        if ($section === 'revenue') {
            $stats['revenue_by_plan'] = [
                'basic' => \App\Models\Company::where('subscription_plan', 'basic')->count() * 29.99,
                'professional' => \App\Models\Company::where('subscription_plan', 'professional')->count() * 79.99,
                'enterprise' => \App\Models\Company::where('subscription_plan', 'enterprise')->count() * 199.99,
            ];
            $stats['total_revenue'] = array_sum($stats['revenue_by_plan']);
            $stats['monthly_recurring_revenue'] = $stats['total_revenue'];
        }

        return $stats;
    }

    private function getCompanyAdminStats($user)
    {
        $companyId = $user->company_id;
        $section = request()->get('section', 'overview');
        
        $stats = [
            'total_employees' => EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->count(),
            'pending_leaves' => LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('status', 'pending')->count(),
            'today_attendance' => AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('date', Carbon::today())->count(),
            'section' => $section,
        ];

        if ($section === 'stats') {
            // Quick Stats
            $stats['active_employees'] = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->where('employment_status', 'active')->count();
            
            $stats['total_departments'] = \App\Models\Department::where('company_id', $companyId)->count();
            $stats['total_positions'] = \App\Models\Position::where('company_id', $companyId)->count();
            $stats['approved_leaves'] = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('status', 'approved')->whereMonth('created_at', now()->month)->count();
        }

        if ($section === 'activities') {
            // Recent Activities
            $stats['recent_leaves'] = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->latest()->limit(5)->get();
            
            $stats['recent_employees'] = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->latest()->limit(5)->get();
        }

        return $stats;
    }

    private function getHrManagerStats($user)
    {
        $companyId = $user->company_id;
        $section = request()->get('section', 'overview');
        
        $stats = [
            'total_employees' => EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->count(),
            'pending_leaves' => LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('status', 'pending')->count(),
            'today_attendance' => AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('date', Carbon::today())->count(),
            'section' => $section,
        ];

        if ($section === 'stats') {
            // Quick Stats
            $stats['active_employees'] = EmployeeProfile::whereHas('user', function($q) use ($companyId) {
                $q->where('company_id', $companyId)->where('status', 'active');
            })->where('employment_status', 'active')->count();
            
            $stats['total_departments'] = \App\Models\Department::where('company_id', $companyId)->count();
            $stats['total_positions'] = \App\Models\Position::where('company_id', $companyId)->count();
            $stats['approved_leaves'] = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('status', 'approved')->whereMonth('created_at', now()->month)->count();
            $stats['expiring_documents'] = Document::where('company_id', $companyId)
                ->whereNotNull('expiry_date')
                ->where('expiry_date', '<=', Carbon::now()->addDays(30))
                ->where('expiry_date', '>=', Carbon::today())
                ->count();
        }

        if ($section === 'pending') {
            // Pending Actions
            $stats['pending_leave_requests'] = LeaveRequest::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })->where('status', 'pending')
            ->with(['employee.user', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
            // Unverified documents (pending verification)
            $stats['unverified_documents'] = Document::where('company_id', $companyId)
                ->where('is_verified', false)
                ->count();
            
            $stats['late_arrivals_today'] = AttendanceRecord::whereHas('employee', function($q) use ($companyId) {
                $q->whereHas('user', function($q2) use ($companyId) {
                    $q2->where('company_id', $companyId);
                });
            })
            ->where('date', Carbon::today())
            ->where('is_late', true)
            ->with('employee.user')
            ->count();
        }

        return $stats;
    }

    private function getManagerStats($user)
    {
        $employeeProfile = $user->employeeProfile;
        
        if (!$employeeProfile) {
            return [];
        }

        $teamMembers = EmployeeProfile::where('manager_id', $user->id)->get();
        
        return [
            'team_size' => $teamMembers->count(),
            'pending_approvals' => LeaveRequest::whereIn('employee_id', $teamMembers->pluck('id'))
                ->where('status', 'pending')->count(),
            'team_attendance_today' => AttendanceRecord::whereIn('employee_id', $teamMembers->pluck('id'))
                ->where('date', Carbon::today())->count(),
        ];
    }

    private function getEmployeeStats($user)
    {
        $employeeProfile = $user->employeeProfile;
        
        if (!$employeeProfile) {
            return [];
        }

        $currentYear = Carbon::now()->year;
        
        return [
            'my_leaves' => LeaveRequest::where('employee_id', $employeeProfile->id)
                ->whereYear('created_at', $currentYear)->count(),
            'pending_leaves' => LeaveRequest::where('employee_id', $employeeProfile->id)
                ->where('status', 'pending')->count(),
            'attendance_this_month' => AttendanceRecord::where('employee_id', $employeeProfile->id)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)->count(),
        ];
    }
}
