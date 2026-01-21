<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Jobs\SendLoginOtp;
use App\Mail\LoginOtpMail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register custom login response
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = auth()->user();
                
                // Generate 4-digit OTP code
                $otpCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                
                // Store OTP in database
                $user->update([
                    'otp_code' => $otpCode,
                    'otp_expires_at' => now()->addMinutes(5),
                ]);
                
                // Send OTP email asynchronously via queue
                SendLoginOtp::dispatch($user->email, $user->name, $otpCode);
                
                // Store intended URL in session for after OTP verification
                $intendedUrl = $user->isAdmin() ? route('admin.dashboard') : route('cars.index');
                session()->put('otp_intended_url', $intendedUrl);
                
                // Redirect to home page with flag to show OTP form in modal
                return redirect()->route('cars.index')->with('showOtpVerification', true);
            }
        });

        // Register custom registration response - redirect to home page
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                // Redirect to home page after successful registration
                return redirect()->route('cars.index')->with('registrationSuccess', true);
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
        $this->configureAuthentication();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        // Redirect customer login to home page (they should use modal)
        Fortify::loginView(function () {
            return redirect()->route('cars.index');
        });
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        // Redirect customer registration to home page (they should use modal)
        Fortify::registerView(function () {
            return redirect()->route('cars.index');
        });
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        // Redirect forgot password to home page (they should use modal)
        Fortify::requestPasswordResetLinkView(function () {
            return redirect()->route('cars.index')->with('showForgotPassword', true);
        });
    }

    /**
     * Configure authentication redirects based on user role.
     */
    private function configureAuthentication(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
