<?php

if (!function_exists('currentCompany')) {
    /**
     * Get the current company from the request context
     */
    function currentCompany()
    {
        return config('app.current_company_model');
    }
}

if (!function_exists('currentCompanyId')) {
    /**
     * Get the current company ID from the request context
     */
    function currentCompanyId()
    {
        return config('app.current_company');
    }
}

if (!function_exists('isPlatform')) {
    /**
     * Check if current request is on the main platform (Super Admin)
     */
    function isPlatform(): bool
    {
        return config('app.tenant_type') === 'platform';
    }
}

if (!function_exists('isTenant')) {
    /**
     * Check if current request is on a company subdomain
     */
    function isTenant(): bool
    {
        return config('app.tenant_type') === 'company';
    }
}
