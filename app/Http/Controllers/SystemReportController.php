<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemReportController extends Controller
{

    public function analytics()
    {
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'total_users' => User::count(),
            'companies_by_plan' => Company::select('subscription_plan', DB::raw('count(*) as count'))
                ->whereNotNull('subscription_plan')
                ->groupBy('subscription_plan')
                ->get(),
            'companies_by_status' => Company::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'new_companies_this_month' => Company::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
        
        return view('super-admin.reports.analytics', compact('stats'));
    }

    public function usage()
    {
        $usage = [
            'total_companies' => Company::count(),
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'companies_with_users' => Company::has('users')->count(),
            'average_users_per_company' => Company::withCount('users')->get()->avg('users_count'),
            'top_companies_by_users' => Company::withCount('users')
                ->orderBy('users_count', 'desc')
                ->limit(10)
                ->get(),
        ];
        
        return view('super-admin.reports.usage', compact('usage'));
    }

    public function performance()
    {
        $performance = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'suspended_companies' => Company::where('status', 'suspended')->count(),
            'companies_by_subscription' => Company::select('subscription_plan', DB::raw('count(*) as count'))
                ->whereNotNull('subscription_plan')
                ->groupBy('subscription_plan')
                ->get(),
            'recent_activity' => Company::latest()->limit(10)->get(),
        ];
        
        return view('super-admin.reports.performance', compact('performance'));
    }
}
