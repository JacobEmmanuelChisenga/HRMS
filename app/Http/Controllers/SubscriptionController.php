<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function index()
    {
        $companies = Company::with('users')
            ->whereNotNull('subscription_plan')
            ->latest()
            ->paginate(15);
        
        $plans = [
            'basic' => ['name' => 'Basic', 'price' => 29.99, 'features' => ['Up to 50 employees', 'Basic features']],
            'professional' => ['name' => 'Professional', 'price' => 79.99, 'features' => ['Up to 200 employees', 'Advanced features']],
            'enterprise' => ['name' => 'Enterprise', 'price' => 199.99, 'features' => ['Unlimited employees', 'All features', 'Priority support']],
        ];
        
        return view('super-admin.subscriptions.index', compact('companies', 'plans'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'subscription_plan' => 'required|string|max:50',
            'subscription_expires_at' => 'nullable|date',
        ]);

        $company->update($validated);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }
}
