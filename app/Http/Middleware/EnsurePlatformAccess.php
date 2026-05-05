<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformAccess
{
    /**
     * Handle an incoming request.
     * Ensures Super Admin routes are only accessible from main platform domain
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantType = config('app.tenant_type', 'company');
        
        // Super Admin routes should only be accessible from main platform
        if ($tenantType !== 'platform') {
            abort(403, 'Super Admin access is only available from the main platform domain.');
        }

        return $next($request);
    }
}
