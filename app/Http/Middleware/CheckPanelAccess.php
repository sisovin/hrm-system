<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPanelAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $panel): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('filament.' . $panel . '.auth.login');
        }

        // Define panel access rules
        $panelRoles = [
            'admin' => ['super_admin', 'admin'],
            'hr' => ['super_admin', 'admin', 'hr_manager'],
            'employee' => ['super_admin', 'admin', 'hr_manager', 'employee'],
        ];

        if (!isset($panelRoles[$panel])) {
            abort(403, 'Invalid panel.');
        }

        $allowedRoles = $panelRoles[$panel];
        $hasAccess = false;

        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            abort(403, 'You do not have permission to access this panel.');
        }

        return $next($request);
    }
}
