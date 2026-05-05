<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        
        // Get system roles (company_id is null) and company-specific roles
        $roles = Role::where(function($q) use ($companyId) {
            $q->whereNull('company_id') // System roles
              ->orWhere('company_id', $companyId); // Company roles
        })
        ->withCount('users')
        ->orderByRaw('company_id IS NULL DESC') // System roles first
        ->orderBy('name')
        ->get();
        
        return view('settings.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('settings.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                }),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // Generate slug from name if not provided
        $slug = $validated['slug'] ?? Str::slug($validated['name']);

        // Ensure slug is unique for this company
        $slug = $this->ensureUniqueSlug($slug, $companyId);

        $role = Role::create([
            'company_id' => $companyId,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
        ]);

        // Assign permissions if provided
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('settings.roles.index')->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        // Ensure user can only view their company's roles or system roles
        $this->authorizeRoleAccess($role);
        
        $role->load('permissions', 'users');
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        return view('settings.roles.show', compact('role', 'permissions'));
    }

    public function edit(Role $role)
    {
        // Ensure user can only edit their company's roles (not system roles)
        if ($role->company_id !== auth()->user()->company_id) {
            abort(403, 'You can only edit roles created for your company.');
        }
        
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('settings.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Ensure user can only update their company's roles (not system roles)
        if ($role->company_id !== auth()->user()->company_id) {
            abort(403, 'You can only edit roles created for your company.');
        }
        
        $companyId = auth()->user()->company_id;
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })->ignore($role->id),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        // Generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Ensure slug is unique for this company
        $validated['slug'] = $this->ensureUniqueSlug($validated['slug'], $companyId, $role->id);

        $role->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
        ]);

        // Update permissions
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('settings.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        // Ensure user can only delete their company's roles (not system roles)
        if ($role->company_id !== auth()->user()->company_id) {
            abort(403, 'You can only delete roles created for your company.');
        }
        
        // Prevent deletion if role has users
        if ($role->users()->where('company_id', auth()->user()->company_id)->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete role. There are users assigned to this role.');
        }

        $role->delete();

        return redirect()->route('settings.roles.index')->with('success', 'Role deleted successfully');
    }

    private function ensureUniqueSlug(string $slug, int $companyId, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (Role::where('company_id', $companyId)
            ->where('slug', $slug)
            ->when($excludeId, function($q) use ($excludeId) {
                $q->where('id', '!=', $excludeId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function authorizeRoleAccess(Role $role)
    {
        $companyId = auth()->user()->company_id;
        
        // Allow access to system roles (company_id is null) or company's own roles
        if ($role->company_id !== null && $role->company_id !== $companyId) {
            abort(403, 'You do not have access to this role.');
        }
    }
}
