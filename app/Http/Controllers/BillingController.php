<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class BillingController extends Controller
{

    public function index()
    {
        $companies = Company::with('users')
            ->whereNotNull('subscription_plan')
            ->latest()
            ->paginate(15);
        
        return view('super-admin.billing.index', compact('companies'));
    }
}
