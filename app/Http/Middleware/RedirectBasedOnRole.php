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
            
            // Admin redirects
            if ($user->isAdmin()) {
                // If admin trying to access non-admin routes (except logout and public pages)
                if (!$request->is('admin/*') && !$request->is('logout') && !$request->is('lang/*')) {
                    return redirect()->route('admin.dashboard');
                }
            }
            
            // Dealer redirects - dealers now use admin interface
            if ($user->isDealer()) {
                // Allow dealers to access all admin routes
                if ($request->is('admin/*')) {
                    // Allow access to all admin routes
                } elseif ($request->is('lender/*')) {
                    // If dealer trying to access lender routes
                    return redirect()->route('admin.dashboard');
                } elseif (!$request->is('admin/*') && !$request->is('logout') && !$request->is('lang/*')) {
                // If dealer trying to access home/public pages (except logout)
                    return redirect()->route('admin.dashboard');
                }
            }
            
            // Lender redirects
            if ($user->isLender()) {
                // If lender trying to access admin or dealer routes
                if ($request->is('admin/*') || $request->is('dealer/*')) {
                    return redirect()->route('lender.dashboard');
                }
                // If lender trying to access home/public pages (except logout)
                if (!$request->is('lender/*') && !$request->is('logout') && !$request->is('lang/*')) {
                    return redirect()->route('lender.dashboard');
                }
            }
            
            // Regular user redirects
            if ($user->isUser()) {
                // If regular user trying to access admin, dealer, or lender routes
                if ($request->is('admin/*') || $request->is('dealer/*') || $request->is('lender/*')) {
                    return redirect()->route('cars.index');
                }
            }
        }
        
        return $next($request);
    }
}

