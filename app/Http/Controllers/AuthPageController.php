<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class AuthPageController extends Controller
{
    /**
     * Show the edit form for auth pages (Company Admin or Super Admin)
     */
    public function edit(Company $company = null)
    {
        $user = auth()->user();
        
        // Super Admin can edit any company, Company Admin can only edit their own
        if ($user->hasRole('super_admin')) {
            // Super Admin: require company parameter
            if (!$company) {
                abort(404, 'Company not specified');
            }
        } else {
            // Company Admin: use their own company
            $company = Company::findOrFail($user->company_id);
        }
        
        return view('auth-pages.edit', compact('company'));
    }

    /**
     * Update the auth pages (Company Admin or Super Admin)
     */
    public function update(Request $request, Company $company = null)
    {
        $user = auth()->user();
        
        // Super Admin can update any company, Company Admin can only update their own
        if ($user->hasRole('super_admin')) {
            // Super Admin: require company parameter from route
            if (!$company) {
                abort(404, 'Company not specified');
            }
        } else {
            // Company Admin: use their own company
            $company = Company::findOrFail($user->company_id);
        }

        $request->validate([
            // Login Page
            'login_page_title' => ['nullable', 'string', 'max:255'],
            'login_page_subtitle' => ['nullable', 'string', 'max:500'],
            'login_page_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            
            // Registration Page
            'registration_page_title' => ['nullable', 'string', 'max:255'],
            'registration_page_subtitle' => ['nullable', 'string', 'max:500'],
            'registration_page_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $data = $request->only([
            'login_page_title',
            'login_page_subtitle',
            'registration_page_title',
            'registration_page_subtitle',
        ]);

        // Handle login page image upload
        if ($request->hasFile('login_page_image')) {
            // Delete old image if exists
            if ($company->login_page_image) {
                Storage::disk('public')->delete($company->login_page_image);
            }
            
            $data['login_page_image'] = $request->file('login_page_image')
                ->store('auth-pages', 'public');
        }

        // Handle login page image removal
        if ($request->has('remove_login_page_image') && $request->remove_login_page_image) {
            if ($company->login_page_image) {
                Storage::disk('public')->delete($company->login_page_image);
            }
            $data['login_page_image'] = null;
        }

        // Handle registration page image upload
        if ($request->hasFile('registration_page_image')) {
            // Delete old image if exists
            if ($company->registration_page_image) {
                Storage::disk('public')->delete($company->registration_page_image);
            }
            
            $data['registration_page_image'] = $request->file('registration_page_image')
                ->store('auth-pages', 'public');
        }

        // Handle registration page image removal
        if ($request->has('remove_registration_page_image') && $request->remove_registration_page_image) {
            if ($company->registration_page_image) {
                Storage::disk('public')->delete($company->registration_page_image);
            }
            $data['registration_page_image'] = null;
        }

        $company->update($data);

        // Redirect based on role
        if ($user->hasRole('super_admin')) {
            return redirect()->route('super-admin.companies.auth-pages', $company->id)
                ->with('success', 'Authentication pages updated successfully');
        }
        
        return redirect()->route('settings.auth-pages.edit')
            ->with('success', 'Authentication pages updated successfully');
    }
}
