<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('users')->latest()->paginate(15);
        
        return view('super-admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('super-admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => [
                'required',
                'string',
                'max:63', // Max subdomain length per RFC
                'regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/', // Valid subdomain format
                Rule::unique('companies', 'subdomain')->whereNull('deleted_at'),
                'not_in:www,admin,api,mail,ftp,localhost,test,staging,production', // Reserved subdomains
            ],
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
            'subscription_plan' => 'nullable|string|max:50',
            'subscription_expires_at' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            // Company Admin User fields
            'admin_first_name' => 'required|string|max:255',
            'admin_last_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        // Create the company
        $company = Company::create([
            'name' => $validated['name'],
            'subdomain' => strtolower($validated['subdomain']), // Ensure lowercase
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'status' => $validated['status'],
            'subscription_plan' => $validated['subscription_plan'] ?? null,
            'subscription_expires_at' => $validated['subscription_expires_at'] ?? null,
            'logo' => $validated['logo'] ?? null,
            'primary_color' => $validated['primary_color'] ?? null,
            'secondary_color' => $validated['secondary_color'] ?? null,
            'accent_color' => $validated['accent_color'] ?? null,
        ]);

        // Get the company_admin role
        $companyAdminRole = Role::where('slug', 'company_admin')->first();

        if ($companyAdminRole) {
            // Create the company admin user
            $adminUser = User::create([
                'company_id' => $company->id,
                'role_id' => $companyAdminRole->id,
                'first_name' => $validated['admin_first_name'],
                'last_name' => $validated['admin_last_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'status' => 'active',
                'email_verified_at' => now(), // Auto-verify for company admin
            ]);
        }

        // Build login URL based on environment
        $appUrl = config('app.url', 'http://localhost');
        $loginUrl = str_replace(['http://', 'https://'], '', $appUrl);
        $loginUrl = str_replace('localhost', $company->subdomain . '.localhost', $loginUrl);
        $loginUrl = str_replace('127.0.0.1:8000', $company->subdomain . '.127.0.0.1:8000', $loginUrl);
        
        // For production, use proper domain structure
        if (!str_contains($appUrl, 'localhost') && !str_contains($appUrl, '127.0.0.1')) {
            $domain = parse_url($appUrl, PHP_URL_HOST);
            $loginUrl = $company->subdomain . '.' . $domain;
        }
        
        $fullLoginUrl = (str_starts_with($appUrl, 'https') ? 'https://' : 'http://') . $loginUrl . '/login';

        return redirect()->route('companies.index')
            ->with('success', "Company and Company Admin created successfully. Login URL: {$fullLoginUrl}")
            ->with('login_url', $fullLoginUrl)
            ->with('admin_email', $validated['admin_email']);
    }

    public function show(Company $company)
    {
        $company->load(['users', 'departments', 'positions']);
        
        return view('super-admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('super-admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('companies', 'subdomain')->whereNull('deleted_at')->ignore($company->id)
            ],
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended',
            'subscription_plan' => 'nullable|string|max:50',
            'subscription_expires_at' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company->update($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        // The Company model's boot method will handle file deletions
        // Database foreign keys with cascade will handle all related data
        
        // Force delete to permanently remove (bypass soft delete)
        // This ensures all related data is completely removed
        $company->forceDelete();

        return redirect()->route('companies.index')
            ->with('success', 'Company and all related data deleted successfully.');
    }

    public function settings()
    {
        // Get all companies with their statistics
        $companies = Company::withCount(['users', 'departments', 'positions'])
            ->with(['users' => function($query) {
                $query->where('status', 'active');
            }])
            ->latest()
            ->get();

        // Calculate platform statistics
        $stats = [
            'total_companies' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'inactive_companies' => Company::where('status', 'inactive')->count(),
            'suspended_companies' => Company::where('status', 'suspended')->count(),
            'total_users' => User::whereNotNull('company_id')->count(),
            'active_users' => User::whereNotNull('company_id')->where('status', 'active')->count(),
        ];

        return view('super-admin.companies.settings', compact('companies', 'stats'));
    }
}
