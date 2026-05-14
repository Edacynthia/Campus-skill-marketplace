<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow access to approval.pending route - pending users need this
        if ($request->routeIs('approval.pending')) {
            return $next($request);
        }

        // Allow access to logout
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        // Allow access if user is not authenticated
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Allow university email users who are approved
        if ($user->isUniversityEmail()) {
            if ($user->isApproved()) {
                return $next($request);
            }
        }

        // For non-university users
        if (!$user->isUniversityEmail()) {
            // If approved by admin, allow access
            if ($user->isApproved()) {
                return $next($request);
            }

            // If pending approval, redirect to pending page
            if ($user->isPendingApproval()) {
                return redirect()->route('approval.pending');
            }

            // If rejected, logout and show error
            if ($user->isRejected()) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Your account has been rejected. Please contact support for more information.');
            }
        }

        return $next($request);
    }
}
