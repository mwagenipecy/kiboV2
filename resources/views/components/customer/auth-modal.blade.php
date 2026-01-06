<!-- Auth Side Modal -->
<div id="authModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div id="authBackdrop" 
    class="absolute inset-0 bg-black/50  transition-opacity">

</div>
    
    
    <!-- Modal Panel -->
    <div id="authPanel" class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="p-6">
            <!-- Close Button -->
            <button id="closeAuthModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Logo -->
            <div class="mb-8 text-center">
                <img src="{{ asset('logo/green.png') }}" alt="Kibo Auto" class="h-12 mx-auto mb-4">
                <h2 id="authTitle" class="text-2xl font-bold text-gray-900">{{ __('auth.sign_in') }}</h2>
                <p id="authSubtitle" class="text-gray-600 mt-2">{{ __('auth.welcome_back') }}</p>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="space-y-4">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-red-600 font-medium">
                                    @if ($errors->has('email'))
                                        {{ $errors->first('email') }}
                                    @else
                                        {{ __('auth.credentials_not_match') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email_address') }}</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.enter_email') }}"
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.password') }}</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-3 pr-10 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                placeholder="{{ __('auth.enter_password') }}"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password', 'password_toggle')"
                                id="password_toggle"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <svg id="password_eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="password_eye_slash" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
                        </label>
                        <button type="button" id="showForgotPasswordForm" class="text-sm text-green-700 hover:text-green-800">{{ __('auth.forgot_password') }}</button>
                    </div>

                    <!-- Sign In Button -->
                    <button 
                        type="submit" 
                        id="loginSubmitBtn"
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                        <span id="loginSubmitText">{{ __('auth.sign_in') }}</span>
                        <span id="loginSubmitLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Signing in...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">{{ __('auth.dont_have_account') }}</span>
                    </div>
                </div>

                <!-- Create Account Button -->
                <button 
                    id="showRegisterForm" 
                    class="w-full border-2 border-green-700 text-green-700 hover:bg-green-50 py-3 rounded-lg font-medium transition-colors"
                >
                    {{ __('auth.create_account') }}
                </button>
            </div>

            <!-- Registration Form (Hidden by default) -->
            <div id="registerForm" class="space-y-4 hidden">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <!-- Display success message -->
                    @if (session('registrationSuccess'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-green-700 font-medium">Registration successful! You can now sign in.</span>
                            </div>
                        </div>
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any() && old('name'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="reg_name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.full_name') }}</label>
                        <input 
                            type="text" 
                            id="reg_name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.enter_full_name') }}"
                        >
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email_address') }}</label>
                        <input 
                            type="email" 
                            id="reg_email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.enter_email') }}"
                        >
                    </div>

                    <!-- NIDA Number -->
                    <div class="mb-4">
                        <label for="reg_nida_number" class="block text-sm font-medium text-gray-700 mb-2">NIDA Number</label>
                        <input 
                            type="text" 
                            id="reg_nida_number" 
                            name="nida_number" 
                            value="{{ old('nida_number') }}"
                            required
                            maxlength="20"
                            pattern="[0-9]{20}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 20)"
                            class="w-full px-4 py-3 border {{ $errors->has('nida_number') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="Enter 20-digit NIDA number"
                        >
                        @error('nida_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Must be exactly 20 digits (numbers only)</p>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label for="reg_phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input 
                            type="tel" 
                            id="reg_phone_number" 
                            name="phone_number" 
                            value="{{ old('phone_number') }}"
                            required
                            maxlength="20"
                            class="w-full px-4 py-3 border {{ $errors->has('phone_number') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="Enter phone number"
                        >
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('auth.password') }}
                            <span class="text-xs text-gray-500 font-normal">(Min 8 chars, uppercase, number, special char)</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="reg_password" 
                                name="password" 
                                required
                                class="w-full px-4 py-3 pr-10 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                placeholder="{{ __('auth.create_password') }}"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('reg_password', 'reg_password_toggle')"
                                id="reg_password_toggle"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <svg id="reg_password_eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="reg_password_eye_slash" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="reg_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.confirm_password') }}</label>
                        <div class="relative">
                            <input 
                                type="password" 
                                id="reg_password_confirmation" 
                                name="password_confirmation" 
                                required
                                class="w-full px-4 py-3 pr-10 border {{ $errors->has('password_confirmation') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                placeholder="{{ __('auth.confirm_your_password') }}"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('reg_password_confirmation', 'reg_password_confirmation_toggle')"
                                id="reg_password_confirmation_toggle"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700"
                            >
                                <svg id="reg_password_confirmation_eye" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="reg_password_confirmation_eye_slash" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox" required class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 mt-1">
                            <span class="ml-2 text-sm text-gray-600">
                                {!! __('auth.terms_agreement', ['terms' => '<a href="#" class="text-green-700 hover:underline">' . __('auth.terms_of_service') . '</a>', 'privacy' => '<a href="#" class="text-green-700 hover:underline">' . __('auth.privacy_policy') . '</a>']) !!}
                            </span>
                        </label>
                    </div>

                    <!-- Create Account Button -->
                    <button 
                        type="submit" 
                        id="registerSubmitBtn"
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                        <span id="registerSubmitText">{{ __('auth.create_account') }}</span>
                        <span id="registerSubmitLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating account...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">{{ __('auth.already_have_account') }}</span>
                    </div>
                </div>

                <!-- Back to Sign In Button -->
                <button 
                    id="showLoginForm" 
                    class="w-full border-2 border-green-700 text-green-700 hover:bg-green-50 py-3 rounded-lg font-medium transition-colors"
                >
                    {{ __('auth.sign_in') }}
                </button>
            </div>

            <!-- Forgot Password Form (Hidden by default) -->
            <div id="forgotPasswordForm" class="space-y-4 hidden">
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    <!-- Display success message -->
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-green-700 font-medium">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any() && !old('name') && !old('email') && !session('status'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <p class="text-sm text-gray-600 mb-6">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="forgot_email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email_address') }}</label>
                        <input 
                            type="email" 
                            id="forgot_email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.enter_email') }}"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Send Reset Link Button -->
                    <button 
                        type="submit" 
                        id="forgotPasswordSubmitBtn"
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                        <span id="forgotPasswordSubmitText">Send Password Reset Link</span>
                        <span id="forgotPasswordSubmitLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        </span>
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Remember your password?</span>
                    </div>
                </div>

                <!-- Back to Sign In Button -->
                <button 
                    id="showLoginFormFromForgot" 
                    class="w-full border-2 border-green-700 text-green-700 hover:bg-green-50 py-3 rounded-lg font-medium transition-colors"
                >
                    {{ __('auth.sign_in') }}
                </button>
            </div>

            <!-- OTP Verification Form (Hidden by default) -->
            <div id="otpVerificationForm" class="space-y-4 hidden">
                @if(auth()->check())
                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf
                    <input type="hidden" name="from_modal" value="1">
                    
                    <!-- Display success message -->
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-sm text-green-700 font-medium">{{ session('status') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any() && !old('name') && !old('email'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="text-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Verify Your Email</h3>
                        <p class="text-sm text-gray-600">
                            We've sent a 4-digit verification code to<br>
                            <span class="font-medium text-gray-900">{{ auth()->user()->email }}</span>
                        </p>
                    </div>

                    <!-- OTP Input -->
                    <div class="mb-6">
                        <label for="otp_code_label" class="block text-sm font-medium text-gray-700 mb-3 text-center">
                            Enter Verification Code
                        </label>
                        <div class="flex justify-center gap-3 mb-2">
                            @for($i = 0; $i < 4; $i++)
                                <input
                                    type="text"
                                    name="otp_digit_{{$i}}"
                                    id="otp_digit_{{$i}}"
                                    maxlength="1"
                                    pattern="[0-9]"
                                    class="w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600"
                                    autocomplete="off"
                                    {{ $i === 0 ? 'autofocus' : '' }}
                                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); updateOtpCode(); if(this.value.length === 1) { const next = this.nextElementSibling; if(next && next.tagName === 'INPUT') next.focus(); }"
                                    onkeydown="if(event.key === 'Backspace' && !this.value) { const prev = this.previousElementSibling; if(prev && prev.tagName === 'INPUT') prev.focus(); }"
                                >
                            @endfor
                            <input type="hidden" name="otp_code" id="otp_code">
                        </div>
                        <p class="text-xs text-gray-500 text-center">
                            Code expires in 5 minutes
                        </p>
                    </div>

                    <!-- Verify Button -->
                    <button
                        type="submit"
                        id="otpVerifyBtn"
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                    >
                        <span id="otpVerifyText">Verify & Continue</span>
                        <span id="otpVerifyLoading" class="hidden flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Verifying...
                        </span>
                    </button>
                </form>

                <!-- Resend OTP -->
                <div class="text-center">
                    <form action="{{ route('otp.resend') }}" method="POST" class="inline" id="resendOtpForm">
                        @csrf
                        <input type="hidden" name="from_modal" value="1">
                        <button type="submit" id="resendOtpBtn" class="text-sm text-green-700 hover:text-green-800 font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="resendOtpText">Resend Code</span>
                            <span id="resendOtpLoading" class="hidden flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Sending...
                            </span>
                        </button>
                    </form>
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-gray-600">Please log in first to verify OTP.</p>
                    <button 
                        id="showLoginFormFromOtp" 
                        class="mt-4 text-sm text-green-700 hover:text-green-800 font-medium"
                    >
                        Go to Login
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const authModal = document.getElementById('authModal');
    const authPanel = document.getElementById('authPanel');
    const authBackdrop = document.getElementById('authBackdrop');
    const closeAuthModal = document.getElementById('closeAuthModal');
    const openAuthModal = document.getElementById('openAuthModal');
    const showRegisterForm = document.getElementById('showRegisterForm');
    const showLoginForm = document.getElementById('showLoginForm');
    const showLoginFormFromForgot = document.getElementById('showLoginFormFromForgot');
    const showLoginFormFromOtp = document.getElementById('showLoginFormFromOtp');
    const showForgotPasswordForm = document.getElementById('showForgotPasswordForm');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const otpVerificationForm = document.getElementById('otpVerificationForm');
    const authTitle = document.getElementById('authTitle');
    const authSubtitle = document.getElementById('authSubtitle');

    // Check for OTP verification first (highest priority after login)
    // Check if user is logged in and either has showOtpVerification flag OR has an unverified OTP
    // But NOT if OTP is already verified
    @php
        $user = auth()->user();
        $hasPendingOtp = $user && $user->otp_code && (!$user->otp_expires_at || $user->otp_expires_at->isFuture());
        $shouldShowOtp = (session('showOtpVerification') && auth()->check() && !session('otp_verified')) || ($hasPendingOtp && !session('otp_verified'));
    @endphp
    @if ($shouldShowOtp)
        // Show OTP verification form if user is logged in and showOtpVerification flag is set or has pending OTP
        authModal.classList.remove('hidden');
        setTimeout(() => {
            authPanel.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
        loginForm.classList.add('hidden');
        registerForm.classList.add('hidden');
        forgotPasswordForm.classList.add('hidden');
        otpVerificationForm.classList.remove('hidden');
        authTitle.textContent = 'Verify Your Email';
        authSubtitle.textContent = 'Enter the code sent to your email';
        // Focus on first OTP input
        setTimeout(() => {
            const firstOtpInput = document.getElementById('otp_digit_0');
            if (firstOtpInput) {
                firstOtpInput.focus();
            }
        }, 100);
    // Auto-open modal if there are validation errors, status messages, showForgotPassword flag, or registration success
    // But NOT if OTP was just verified (to prevent showing modal after successful OTP verification)
    @elseif (($errors->any() || session('status') || session('showForgotPassword') || session('registrationSuccess')) && !session('otp_verified') && !session('otpVerified'))
        authModal.classList.remove('hidden');
        setTimeout(() => {
            authPanel.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
        
        // Show registration form if name field was submitted (registration attempt) or registration success
        @if (old('name') || session('registrationSuccess'))
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            forgotPasswordForm.classList.add('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.register_title') }}';
            authSubtitle.textContent = '{{ __('auth.join_today') }}';
        @elseif ((session('status') && !session('otpVerified') && !session('otp_verified')) || (session('showForgotPassword') && !session('otp_verified') && !session('otpVerified')) || (request()->routeIs('password.request') && !old('name')))
            // Show forgot password form if status message exists, showForgotPassword flag, or on password request route
            // But NOT if OTP was just verified
            loginForm.classList.add('hidden');
            registerForm.classList.add('hidden');
            forgotPasswordForm.classList.remove('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.forgot_password') }}';
            authSubtitle.textContent = 'Enter your email to receive a password reset link';
        @endif
    @endif
    
    // Close modal if OTP was verified successfully
    @if (session('otpVerified') || session('otp_verified'))
        if (authModal) {
            authPanel.classList.add('translate-x-full');
            setTimeout(() => {
                authModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
    @endif

    // Open modal - handle both button ID and Livewire component button
    const openAuthModalButtons = document.querySelectorAll('#openAuthModal, [id="openAuthModal"]');
    openAuthModalButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (authModal) {
                authModal.classList.remove('hidden');
                setTimeout(() => {
                    if (authPanel) {
                        authPanel.classList.remove('translate-x-full');
                    }
                }, 10);
                document.body.style.overflow = 'hidden';
            }
        });
    });

    // Close modal
    function closeModal() {
        authPanel.classList.add('translate-x-full');
        setTimeout(() => {
            authModal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    closeAuthModal.addEventListener('click', closeModal);
    authBackdrop.addEventListener('click', closeModal);

    // Switch to register form
    if (showRegisterForm) {
        showRegisterForm.addEventListener('click', function() {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            forgotPasswordForm.classList.add('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.register_title') }}';
            authSubtitle.textContent = '{{ __('auth.join_today') }}';
        });
    }

    // Switch to login form
    if (showLoginForm) {
        showLoginForm.addEventListener('click', function() {
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            forgotPasswordForm.classList.add('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.auth_title') }}';
            authSubtitle.textContent = '{{ __('auth.welcome_back') }}';
        });
    }

    // Switch to login form from forgot password
    if (showLoginFormFromForgot) {
        showLoginFormFromForgot.addEventListener('click', function() {
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            forgotPasswordForm.classList.add('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.auth_title') }}';
            authSubtitle.textContent = '{{ __('auth.welcome_back') }}';
        });
    }

    // Switch to login form from OTP
    if (showLoginFormFromOtp) {
        showLoginFormFromOtp.addEventListener('click', function() {
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            forgotPasswordForm.classList.add('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.auth_title') }}';
            authSubtitle.textContent = '{{ __('auth.welcome_back') }}';
        });
    }

    // Switch to forgot password form
    if (showForgotPasswordForm) {
        showForgotPasswordForm.addEventListener('click', function(e) {
            e.preventDefault();
            loginForm.classList.add('hidden');
            registerForm.classList.add('hidden');
            forgotPasswordForm.classList.remove('hidden');
            otpVerificationForm.classList.add('hidden');
            authTitle.textContent = '{{ __('auth.forgot_password') }}';
            authSubtitle.textContent = 'Enter your email to receive a password reset link';
        });
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !authModal.classList.contains('hidden')) {
            closeModal();
        }
    });
});

// Password visibility toggle function
function togglePasswordVisibility(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '_eye');
    const eyeSlash = document.getElementById(inputId + '_eye_slash');
    
    if (input.type === 'password') {
        input.type = 'text';
        if (eye) eye.classList.add('hidden');
        if (eyeSlash) eyeSlash.classList.remove('hidden');
    } else {
        input.type = 'password';
        if (eye) eye.classList.remove('hidden');
        if (eyeSlash) eyeSlash.classList.add('hidden');
    }
}

// Update OTP code hidden input
function updateOtpCode() {
    const inputs = document.querySelectorAll('input[name^="otp_digit_"]');
    const hiddenInput = document.getElementById('otp_code');
    if (hiddenInput) {
        let code = '';
        inputs.forEach(input => {
            code += input.value || '';
        });
        hiddenInput.value = code;
    }
}

// Handle paste for OTP inputs
document.addEventListener('paste', function(e) {
    const otpInputs = document.querySelectorAll('input[name^="otp_digit_"]');
    if (otpInputs.length > 0 && Array.from(otpInputs).includes(e.target)) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 4);
        for (let i = 0; i < pastedData.length && i < otpInputs.length; i++) {
            otpInputs[i].value = pastedData[i];
        }
        updateOtpCode();
        if (pastedData.length < otpInputs.length) {
            otpInputs[pastedData.length].focus();
        }
    }
});

// Add loading states to form buttons
document.addEventListener('DOMContentLoaded', function() {
    // Login form
    const loginForm = document.querySelector('#loginForm form');
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            const btn = document.getElementById('loginSubmitBtn');
            const text = document.getElementById('loginSubmitText');
            const loading = document.getElementById('loginSubmitLoading');
            if (btn && text && loading) {
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');
            }
        });
    }

    // Register form
    const registerForm = document.querySelector('#registerForm form');
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            const btn = document.getElementById('registerSubmitBtn');
            const text = document.getElementById('registerSubmitText');
            const loading = document.getElementById('registerSubmitLoading');
            if (btn && text && loading) {
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');
            }
        });
    }

    // Forgot password form
    const forgotPasswordForm = document.querySelector('#forgotPasswordForm form');
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function() {
            const btn = document.getElementById('forgotPasswordSubmitBtn');
            const text = document.getElementById('forgotPasswordSubmitText');
            const loading = document.getElementById('forgotPasswordSubmitLoading');
            if (btn && text && loading) {
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');
            }
        });
    }

    // OTP verification form
    const otpForm = document.querySelector('#otpVerificationForm form');
    if (otpForm) {
        otpForm.addEventListener('submit', function() {
            const btn = document.getElementById('otpVerifyBtn');
            const text = document.getElementById('otpVerifyText');
            const loading = document.getElementById('otpVerifyLoading');
            if (btn && text && loading) {
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');
            }
        });
    }

    // Resend OTP form
    const resendOtpForm = document.getElementById('resendOtpForm');
    if (resendOtpForm) {
        resendOtpForm.addEventListener('submit', function() {
            const btn = document.getElementById('resendOtpBtn');
            const text = document.getElementById('resendOtpText');
            const loading = document.getElementById('resendOtpLoading');
            if (btn && text && loading) {
                btn.disabled = true;
                text.classList.add('hidden');
                loading.classList.remove('hidden');
            }
        });
    }
});
</script>

