<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    /**
     * Show the company landing page (public, no auth required)
     */
    public function show()
    {
        $company = config('app.current_company_model');
        
        if (!$company) {
            // No company context, show default welcome
            return view('welcome');
        }

        // If landing page is disabled, redirect to login
        if (!$company->landing_page_enabled) {
            if (auth()->check()) {
                return redirect()->route('dashboard');
            }
            return redirect()->route('login');
        }

        return view('landing-pages.show', compact('company'));
    }

    /**
     * Show the edit form for landing page (Company Admin or Super Admin)
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
        
        return view('landing-pages.edit', compact('company'));
    }

    /**
     * Update the landing page (Company Admin or Super Admin)
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
            'landing_page_enabled' => ['boolean'],
            'landing_page_title' => ['nullable', 'string', 'max:255'],
            'landing_page_content' => ['nullable', 'string'],
            'landing_page_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'landing_page_primary_cta_text' => ['nullable', 'string', 'max:100'],
            'landing_page_primary_cta_link' => ['nullable', 'string', 'max:255'],
            'landing_page_secondary_cta_text' => ['nullable', 'string', 'max:100'],
            'landing_page_secondary_cta_link' => ['nullable', 'string', 'max:255'],
        ]);

        $data = $request->only([
            'landing_page_enabled',
            'landing_page_title',
            'landing_page_content',
            'landing_page_primary_cta_text',
            'landing_page_primary_cta_link',
            'landing_page_secondary_cta_text',
            'landing_page_secondary_cta_link',
        ]);

        // Handle image upload
        if ($request->hasFile('landing_page_image')) {
            // Delete old image if exists
            if ($company->landing_page_image) {
                Storage::disk('public')->delete($company->landing_page_image);
            }
            
            $data['landing_page_image'] = $request->file('landing_page_image')
                ->store('landing-pages', 'public');
        }

        // Handle image removal
        if ($request->has('remove_landing_page_image') && $request->remove_landing_page_image) {
            if ($company->landing_page_image) {
                Storage::disk('public')->delete($company->landing_page_image);
            }
            $data['landing_page_image'] = null;
        }

        $company->update($data);

        // Redirect based on role
        if ($user->hasRole('super_admin')) {
            return redirect()->route('super-admin.companies.landing-page', $company->id)
                ->with('success', 'Landing page updated successfully');
        }
        
        return redirect()->route('settings.landing-page.edit')
            ->with('success', 'Landing page updated successfully');
    }
}
