<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AuditTrail;
use App\Models\AttendanceLog;
use App\Models\DocumentAccessLog;
use App\Models\User;
use Carbon\Carbon;

class AuditController extends Controller
{
    public function index()
    {
        return view('audit.index');
    }

    // Audit Trails
    public function allActivities(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = ActivityLog::where('company_id', $companyId)->with('user');

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::where('company_id', $companyId)->get();

        return view('audit.activities.index', compact('activities', 'users'));
    }

    public function userActions(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = ActivityLog::where('company_id', $companyId)
            ->whereNotNull('user_id')
            ->with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $actions = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::where('company_id', $companyId)->get();

        return view('audit.activities.user-actions', compact('actions', 'users'));
    }

    public function dataChanges(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = AuditTrail::where('company_id', $companyId)->with('user');

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $changes = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get unique table names for filter
        $tables = AuditTrail::where('company_id', $companyId)
            ->distinct()
            ->pluck('table_name')
            ->sort()
            ->values();

        return view('audit.activities.data-changes', compact('changes', 'tables'));
    }

    // Document Access Logs
    public function documentAccessLogs(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = DocumentAccessLog::whereHas('document', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->with(['document', 'user']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::where('company_id', $companyId)->get();

        return view('audit.documents.access-logs', compact('logs', 'users'));
    }

    // Attendance Modifications
    public function attendanceModifications(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = AttendanceLog::whereHas('attendanceRecord', function($q) use ($companyId) {
            $q->whereHas('employee', function($q2) use ($companyId) {
                $q2->whereHas('user', function($q3) use ($companyId) {
                    $q3->where('company_id', $companyId);
                });
            });
        })->with(['attendanceRecord.employee.user', 'performedBy']);

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('performed_by')) {
            $query->where('performed_by', $request->performed_by);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $modifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = User::where('company_id', $companyId)->get();

        return view('audit.attendance.modifications', compact('modifications', 'users'));
    }

    // Compliance Reports
    public function complianceReports(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $year = $request->get('year', Carbon::now()->year);
        $month = $request->get('month', Carbon::now()->month);

        // Activity Summary
        $activitySummary = [
            'total_activities' => ActivityLog::where('company_id', $companyId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'user_actions' => ActivityLog::where('company_id', $companyId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereNotNull('user_id')
                ->count(),
            'data_changes' => AuditTrail::where('company_id', $companyId)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count(),
            'document_accesses' => DocumentAccessLog::whereHas('document', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count(),
            'attendance_modifications' => AttendanceLog::whereHas('attendanceRecord', function($q) use ($companyId) {
                $q->whereHas('employee', function($q2) use ($companyId) {
                    $q2->whereHas('user', function($q3) use ($companyId) {
                        $q3->where('company_id', $companyId);
                    });
                });
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count(),
        ];

        // Top Users by Activity
        $topUsers = ActivityLog::where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('user_id')
            ->selectRaw('user_id, count(*) as activity_count')
            ->groupBy('user_id')
            ->with('user')
            ->orderBy('activity_count', 'desc')
            ->limit(10)
            ->get();

        // Most Accessed Documents
        $topDocuments = DocumentAccessLog::whereHas('document', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->selectRaw('document_id, count(*) as access_count')
        ->groupBy('document_id')
        ->with('document')
        ->orderBy('access_count', 'desc')
        ->limit(10)
        ->get();

        return view('audit.compliance.reports', compact('activitySummary', 'topUsers', 'topDocuments', 'year', 'month'));
    }
}
