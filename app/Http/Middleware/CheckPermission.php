<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if (!$user->role) {
            abort(403, 'User does not have a role assigned.');
        }

        $userPermissions = $user->role->permissions->pluck('slug')->toArray();
        
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                abort(403, 'You do not have permission to access this resource.');
            }
        }

        return $next($request);
    }
}
