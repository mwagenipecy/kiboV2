<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If user is admin and trying to access non-admin routes, redirect to admin dashboard
            if ($user->isAdmin() && !$request->is('admin/*')) {
                return redirect()->route('admin.dashboard');
            }
            
            // If user is regular user and trying to access admin routes, redirect to user dashboard
            if ($user->isUser() && $request->is('admin/*')) {
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}

