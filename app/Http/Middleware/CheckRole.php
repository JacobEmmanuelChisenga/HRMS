<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Ensure role relationship is loaded
        if (!$user->relationLoaded('role')) {
            $user->load('role');
        }
        
        if (!$user->role) {
            abort(403, 'User does not have a role assigned.');
        }

        if (!in_array($user->role->slug, $roles)) {
            abort(403, 'Unauthorized access. Required role: ' . implode(' or ', $roles) . '. Your role: ' . $user->role->slug);
        }

        return $next($request);
    }
}
