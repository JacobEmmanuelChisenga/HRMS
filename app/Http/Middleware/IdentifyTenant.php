<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Company;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     * Identifies the company/tenant based on subdomain
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Extract subdomain first (works for both localhost and production)
        $subdomain = $this->extractSubdomain($host);
        
        // If no subdomain, this is the main platform (Super Admin access)
        if (!$subdomain || $subdomain === 'www' || $subdomain === 'admin') {
            // For main platform, allow some routes without tenant context
            if ($request->is('/') || $request->is('login') || $request->is('register')) {
                config(['app.tenant_type' => 'platform']);
                config(['app.current_company' => null]);
                return $next($request);
            }

            // All other routes on main domain are still platform context
            config(['app.tenant_type' => 'platform']);
            config(['app.current_company' => null]);
            return $next($request);
        }
        
        // If we get here, we have a subdomain - need to find the company
        // But first, check if database is available
        if (!$this->isDatabaseAvailable()) {
            // Database not available - allow access as platform for development
            if ($this->isLocalhostOrIp($host)) {
                config(['app.tenant_type' => 'platform']);
                config(['app.current_company' => null]);
                return $next($request);
            }
            abort(503, 'Service temporarily unavailable. Please try again later.');
        }

        // Find company by subdomain
        try {
            $company = Company::where('subdomain', $subdomain)->first();
            
            if (!$company) {
                // Company not found
                \Log::warning("Company not found for subdomain: {$subdomain}");
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Company not found. Please check your subdomain or contact support.'
                    ], 404);
                }
                
                abort(404, "Company with subdomain '{$subdomain}' not found. Please check your subdomain or contact support.");
            }
            
            if ($company->status !== 'active') {
                // Company inactive
                \Log::warning("Company inactive for subdomain: {$subdomain}");
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Company account is inactive. Please contact support.'
                    ], 403);
                }
                
                abort(403, "Company account is {$company->status}. Please contact support.");
            }
        } catch (\Exception $e) {
            // If database error, allow request to continue (might be during setup)
            // Log the error for debugging
            \Log::warning('Tenant identification error: ' . $e->getMessage());
            
            // For localhost/IP, allow access as platform
            if ($this->isLocalhostOrIp($host)) {
                config(['app.tenant_type' => 'platform']);
                config(['app.current_company' => null]);
                return $next($request);
            }
            
            // For production, abort
            abort(500, 'System error. Please contact support.');
        }

        // Set company context
        config(['app.tenant_type' => 'company']);
        config(['app.current_company' => $company->id]);
        config(['app.current_company_model' => $company]);

        // Set company in request for easy access
        $request->merge(['current_company' => $company]);

        return $next($request);
    }

    /**
     * Check if host is localhost or IP address
     */
    private function isLocalhostOrIp(string $host): bool
    {
        return in_array($host, ['localhost', '127.0.0.1', '::1']) 
            || filter_var($host, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Extract subdomain from host
     * Examples:
     * - company1.hrms.test -> company1
     * - company1.hrms.com -> company1
     * - company1.127.0.0.1 -> company1 (development)
     * - company1.localhost -> company1 (development)
     * - hrms.test -> null (main domain)
     * - 127.0.0.1 -> null (main platform)
     * - localhost -> null (main platform)
     */
    private function extractSubdomain(string $host): ?string
    {
        $parts = explode('.', $host);
        
        // Special handling for localhost/IP with subdomain
        // e.g., coreaxis.localhost or coreaxis.127.0.0.1
        if (count($parts) >= 2) {
            $lastPart = $parts[count($parts) - 1];
            
            // If last part is localhost, local, dev, test, or an IP component
            if (in_array($lastPart, ['localhost', 'local', 'dev', 'test']) || 
                is_numeric($lastPart) || 
                filter_var($host, FILTER_VALIDATE_IP) !== false) {
                
                // Check if first part is a valid subdomain (not the hostname itself)
                $firstPart = $parts[0];
                if ($firstPart !== 'localhost' && 
                    $firstPart !== '127' && 
                    $firstPart !== '0' && 
                    $firstPart !== '1' &&
                    !filter_var($firstPart, FILTER_VALIDATE_IP)) {
                    return $firstPart; // First part is subdomain
                }
            }
        }
        
        // Handle pure localhost/IP (no subdomain)
        if ($this->isLocalhostOrIp($host)) {
            return null; // No subdomain, main platform
        }
        
        // If we have 3+ parts, first part is subdomain
        // e.g., company1.hrms.test = [company1, hrms, test]
        if (count($parts) >= 3) {
            return $parts[0];
        }
        
        // If we have 2 parts, check if it's a known TLD
        // For local development: hrms.test (no subdomain)
        // For production: hrms.com (no subdomain)
        if (count($parts) === 2) {
            // Check if second part is a known TLD
            $knownTlds = ['com', 'net', 'org', 'io', 'co'];
            if (in_array($parts[1], $knownTlds)) {
                return null; // Main domain (e.g., hrms.com)
            }
        }
        
        return null;
    }
    
    /**
     * Check if database is available
     */
    private function isDatabaseAvailable(): bool
    {
        try {
            \DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
