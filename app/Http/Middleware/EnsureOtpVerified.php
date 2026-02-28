<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     * If the user is authenticated but has not verified OTP in this session,
     * redirect to OTP verification and do not allow access to dashboard or
     * other protected resources.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        if (session('otp_verified')) {
            return $next($request);
        }

        // User is logged in but OTP not verified - redirect to OTP verification
        session()->put('otp_intended_url', $request->fullUrl());

        return redirect()->route('otp.verify.show');
    }
}
