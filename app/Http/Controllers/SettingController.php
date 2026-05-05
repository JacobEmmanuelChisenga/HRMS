<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    // Company Profile
    public function basicInformation()
    {
        $company = auth()->user()->company;
        return view('settings.company.basic-information', compact('company'));
    }

    public function updateBasicInformation(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $company->update($validated);

        return redirect()->back()->with('success', 'Basic information updated successfully');
    }

    public function branding()
    {
        $company = auth()->user()->company;
        return view('settings.company.branding', compact('company'));
    }

    public function updateBranding(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'subdomain' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('companies', 'subdomain')->whereNull('deleted_at')->ignore($company->id)
            ],
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }

        $company->update($validated);

        return redirect()->back()->with('success', 'Branding updated successfully');
    }

    // User Management
    public function allUsers(Request $request)
    {
        $user = auth()->user();
        $companyId = $user->company_id;

        $query = User::where('company_id', $companyId)->with('role');

        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        // Get unique roles by slug (prevent duplicates)
        $roleIds = Role::selectRaw('MIN(id) as id')
            ->groupBy('slug')
            ->pluck('id');
        $roles = Role::whereIn('id', $roleIds)->orderBy('name')->get();

        return view('settings.users.index', compact('users', 'roles'));
    }

    public function createUser()
    {
        $companyId = auth()->user()->company_id;
        
        // Get system roles (excluding super_admin) and company-specific roles
        // Get unique roles by slug (prevent duplicates)
        $roleIds = Role::where('slug', '!=', 'super_admin')
            ->where(function($q) use ($companyId) {
                $q->whereNull('company_id') // System roles
                  ->orWhere('company_id', $companyId); // Company roles
            })
            ->selectRaw('MIN(id) as id')
            ->groupBy('slug')
            ->pluck('id');
        $roles = Role::whereIn('id', $roleIds)
            ->orderByRaw('company_id IS NULL DESC') // System roles first
            ->orderBy('name')
            ->get();
        
        return view('settings.users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        // Get super_admin role ID to exclude it from validation
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($superAdminRole) {
                    if ($superAdminRole && $value == $superAdminRole->id) {
                        $fail('The selected role is not allowed.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ]);

        User::create([
            'company_id' => auth()->user()->company_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        return redirect()->route('settings.users.index')->with('success', 'User created successfully');
    }

    public function editUser(User $user)
    {
        // Verify user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        $companyId = auth()->user()->company_id;
        
        // Get system roles (excluding super_admin) and company-specific roles
        // Get unique roles by slug (prevent duplicates)
        $roleIds = Role::where('slug', '!=', 'super_admin')
            ->where(function($q) use ($companyId) {
                $q->whereNull('company_id') // System roles
                  ->orWhere('company_id', $companyId); // Company roles
            })
            ->selectRaw('MIN(id) as id')
            ->groupBy('slug')
            ->pluck('id');
        $roles = Role::whereIn('id', $roleIds)
            ->orderByRaw('company_id IS NULL DESC') // System roles first
            ->orderBy('name')
            ->get();
        
        return view('settings.users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user)
    {
        // Verify user belongs to same company
        if ($user->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized');
        }

        // Get super_admin role ID to exclude it from validation
        $superAdminRole = Role::where('slug', 'super_admin')->first();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) use ($superAdminRole) {
                    if ($superAdminRole && $value == $superAdminRole->id) {
                        $fail('The selected role is not allowed.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('settings.users.index')->with('success', 'User updated successfully');
    }

    public function rolesAndPermissions()
    {
        $companyId = auth()->user()->company_id;
        
        // Get system roles and company-specific roles
        $roles = Role::with('permissions')
            ->where(function($q) use ($companyId) {
                $q->whereNull('company_id') // System roles
                  ->orWhere('company_id', $companyId); // Company roles
            })
            ->orderByRaw('company_id IS NULL DESC') // System roles first
            ->orderBy('name')
            ->get();
        
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        return view('settings.users.roles-permissions', compact('roles', 'permissions'));
    }

    // Subscription
    public function currentPlan()
    {
        $company = auth()->user()->company;
        return view('settings.subscription.current-plan', compact('company'));
    }

    public function billingHistory()
    {
        // This would typically fetch from a billing/invoice table
        // For now, return a placeholder view
        return view('settings.subscription.billing-history');
    }

    public function upgradePlan()
    {
        $company = auth()->user()->company;
        $plans = ['basic', 'professional', 'enterprise']; // This would come from a plans table
        return view('settings.subscription.upgrade-plan', compact('company', 'plans'));
    }

    // IT Admin specific methods
    public function integrations()
    {
        return view('settings.integrations');
    }

    public function backups()
    {
        return view('settings.backups');
    }

    public function logs()
    {
        return view('settings.logs');
    }
}
