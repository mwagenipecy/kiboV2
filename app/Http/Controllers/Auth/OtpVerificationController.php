<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('cars.index');
        }

        $user = Auth::user();
        
        // If OTP is already verified in this session, redirect to intended URL
        if (session('otp_verified')) {
            return $this->redirectAfterVerification();
        }

        // Check if OTP exists and hasn't expired
        if (!$user->otp_code || !$user->otp_expires_at || $user->otp_expires_at->isPast()) {
            // Generate new OTP if expired or missing
            $otpCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $user->update([
                'otp_code' => $otpCode,
                'otp_expires_at' => now()->addMinutes(5),
            ]);
            
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($user, $otpCode));
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify the OTP code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:4'],
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('cars.index');
        }

        // Check if OTP matches and hasn't expired
        if ($user->otp_code !== $request->otp_code) {
            throw ValidationException::withMessages([
                'otp_code' => ['The OTP code is invalid.'],
            ]);
        }

        if (!$user->otp_expires_at || $user->otp_expires_at->isPast()) {
            throw ValidationException::withMessages([
                'otp_code' => ['The OTP code has expired. Please request a new one.'],
            ]);
        }

        // Clear OTP from database
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        // Mark OTP as verified in session
        session()->put('otp_verified', true);
        
        // Clear any forgot password flags that might interfere
        session()->forget('showForgotPassword');

        // Redirect after verification regardless of modal or not
        return $this->redirectAfterVerification();
    }

    /**
     * Resend OTP code.
     */
    public function resend()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('cars.index');
        }

        // Generate new OTP code
        $otpCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Store OTP in database
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(5),
        ]);
        
        // Send OTP email
        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($user, $otpCode));

        // Determine redirect based on user role
        $redirectRoute = $user->isAdmin() ? route('admin.dashboard') : route('cars.index');
        
        // If from modal, redirect appropriately with flag
        if (request()->has('from_modal')) {
            return redirect($redirectRoute)
                ->with('showOtpVerification', true)
                ->with('status', 'A new OTP code has been sent to your email.');
        }

        return back()->with('status', 'A new OTP code has been sent to your email.');
    }

    /**
     * Redirect to intended URL after OTP verification.
     */
    private function redirectAfterVerification()
    {
        $intendedUrl = session()->pull('otp_intended_url', route('cars.index'));
        return redirect($intendedUrl)->with('otpVerified', true);
    }
}
