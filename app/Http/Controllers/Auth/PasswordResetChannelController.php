<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SelcomSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class PasswordResetChannelController extends Controller
{
    public function send(Request $request, SelcomSmsService $smsService)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'reset_channel' => ['required', 'in:email,sms'],
        ]);

        if ($validated['reset_channel'] === 'email') {
            Password::sendResetLink(['email' => $validated['email']]);

            return redirect()->route('cars.index')
                ->with('status', __('We have emailed your password reset link.'))
                ->with('showForgotPassword', true);
        }

        $user = User::where('email', $validated['email'])->first();
        $phone = optional($user?->customer)->phone_number;

        if (!$user || empty($phone)) {
            return back()->withErrors([
                'email' => 'No SMS-enabled account found for this email address.',
            ])->withInput();
        }

        $token = Password::broker()->createToken($user);
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        $sent = $smsService->send($phone, "Reset your Kibo Auto password: {$resetUrl}");

        if (!$sent) {
            return back()->withErrors([
                'email' => 'Failed to send password reset SMS. Please try email option.',
            ])->withInput();
        }

        return redirect()->route('cars.index')
            ->with('status', 'Password reset link sent by SMS.')
            ->with('showForgotPassword', true);
    }
}

