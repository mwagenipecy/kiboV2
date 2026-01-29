<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50">
    <!-- Error Modal -->
    @if($showErrorModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">Validation Error</h3>
                    </div>
                    <button wire:click="closeErrorModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <p class="text-gray-600 mb-4 text-sm sm:text-base">Please fix the following errors to continue:</p>
                
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 max-h-60 overflow-y-auto">
                    <ul class="space-y-2">
                        @foreach($errorMessages as $error)
                            <li class="flex items-start gap-2 text-sm text-red-700">
                                <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <button wire:click="closeErrorModal" class="w-full px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors text-sm sm:text-base">
                    Got it, I'll fix these
                </button>
            </div>
        </div>
    @endif

    <!-- Login Modal -->
    @if($showLoginModal)
        <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-[9999] p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 text-center">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Login Required</h2>
                <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">
                    You need to be logged in to submit an import financing request. Please sign in to continue.
                </p>
                <div class="flex flex-col gap-3 items-center">
                    <button wire:click="closeLoginModal" class="inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors text-sm sm:text-base">
                        OK
                    </button>
                
                </div>
            </div>
        </div>
    @endif

    <!-- Side Modal for Login/Register -->
    @if($showSideModal)
        <div class="fixed inset-0 bg-black/50 z-[9999]">
            <div wire:click="closeSideModal" class="absolute inset-0"></div>
            <div class="relative ml-auto w-full max-w-md bg-white shadow-2xl transform transition-transform duration-300 ease-in-out h-full overflow-y-auto z-[10000]">
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                        {{ $sideModalType === 'login' ? 'Sign In' : 'Create Account' }}
                    </h2>
                    <button wire:click="closeSideModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-4 sm:p-6">
                    @if (session()->has('message'))
                        <div class="mb-4 p-3 sm:p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-green-800 text-sm">{{ session('message') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 p-3 sm:p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-red-800 text-sm">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($sideModalType === 'login')
                        <div class="text-center mb-6">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-10 sm:h-12 w-auto">
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Welcome back</h3>
                            <p class="text-sm text-gray-600">Sign in to access your account</p>
                        </div>

                        <form wire:submit.prevent="login" class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input id="email" type="email" wire:model="loginEmail" autocomplete="email" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base" placeholder="you@example.com">
                                </div>
                                @error('loginEmail') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input id="password" type="password" wire:model="loginPassword" autocomplete="current-password" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base" placeholder="Enter your password">
                                </div>
                                @error('loginPassword') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-600 border-gray-300 rounded">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                                </div>
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-green-700 hover:text-green-800">Forgot password?</a>
                                </div>
                            </div>

                            <div>
                                <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-2.5 sm:py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all">
                                    <span wire:loading.remove wire:target="login" class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        Sign in
                                    </span>
                                    <span wire:loading wire:target="login" class="flex items-center">
                                        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Signing in...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <div class="mt-6 flex justify-center">
                            <button wire:click="backToInitialModal" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back
                            </button>
                        </div>
                    @else
                        <div class="text-center mb-6">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-10 sm:h-12 w-auto">
                            </div>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Create an account</h3>
                            <p class="text-sm text-gray-600">Enter your details below to create your account</p>
                        </div>

                        <form wire:submit.prevent="register" class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input id="name" type="text" wire:model="registerName" autocomplete="name" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base" placeholder="Enter your full name">
                                </div>
                                @error('registerName') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input id="reg_email" type="email" wire:model="registerEmail" autocomplete="email" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base" placeholder="you@example.com">
                                </div>
                                @error('registerEmail') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input id="reg_password" type="password" wire:model="registerPassword" autocomplete="new-password" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base" placeholder="Create a password (min 8 characters)">
                                </div>
                                @error('registerPassword') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input id="password_confirmation" type="password" wire:model="registerPasswordConfirmation" autocomplete="new-password" required class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400 text-sm sm:text-base" placeholder="Confirm your password">
                                </div>
                            </div>

                            <div>
                                <button type="submit" wire:loading.attr="disabled" class="w-full flex justify-center items-center py-2.5 sm:py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all">
                                    <span wire:loading.remove wire:target="register" class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                        Create account
                                    </span>
                                    <span wire:loading wire:target="register" class="flex items-center">
                                        <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Creating account...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <div class="mt-6 flex justify-center">
                            <button wire:click="backToInitialModal" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Hero Section -->
    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative h-48 sm:h-56 md:h-64 lg:h-72 rounded-xl sm:rounded-2xl overflow-hidden bg-center bg-cover shadow-lg" style="background-image: url('{{ asset('image/importFinace.png') }}');">
                <div class="absolute inset-0 bg-black/45"></div>
                <div class="relative h-full flex flex-col md:flex-row items-center justify-between gap-4 sm:gap-6 px-4 sm:px-6 lg:px-8 py-6 sm:py-8 text-white">
                    <div class="max-w-2xl text-center md:text-left">
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2">Import Car Financing</h1>
                        <p class="text-gray-100 text-xs sm:text-sm md:text-base">
                            Get financing for importing your dream car or cover tax and transport costs. Quick approval, competitive rates from multiple lenders.
                        </p>
                    </div>
                    <div class="hidden sm:flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-4 sm:px-5 py-2.5 sm:py-3">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-xs sm:text-sm">
                            <div class="font-semibold">Import Financing</div>
                            <div class="text-green-200 text-xs">Multiple Options</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        @if($showSuccess)
            <!-- Success Message -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 p-6 sm:p-8 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Application Submitted!</h2>
                <p class="text-gray-600 mb-4 text-sm sm:text-base">Your import financing request has been received.</p>
                <div class="bg-gray-50 rounded-xl p-4 mb-4 sm:mb-6 inline-block">
                    <span class="text-sm text-gray-500">Reference Number:</span>
                    <div class="text-lg sm:text-xl font-bold text-emerald-600">{{ $referenceNumber }}</div>
                </div>
                <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                    Our team will review your application and you'll receive offers from our partner lenders soon.
                    You can track your request status in <a href="{{ route('import-financing.requests') }}" class="text-emerald-600 hover:underline">My Requests</a>.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <button wire:click="resetForm" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors text-sm sm:text-base">
                        Submit Another Request
                    </button>
                    <a href="{{ route('import-financing.requests') }}" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors text-sm sm:text-base text-center">
                        View My Requests
                    </a>
                </div>
            </div>
        @else
            <!-- Progress Steps - Mobile Version -->
            <div class="mb-6 sm:mb-8 lg:hidden">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Import Financing</h2>
                        <span class="text-xs sm:text-sm text-gray-500 bg-emerald-50 text-emerald-700 px-2 py-1 rounded-full">Step {{ $currentStep }}/4</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 4; $i++)
                            <div class="flex-1 h-2 rounded-full {{ $currentStep >= $i ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>
                        @endfor
                    </div>
                    <div class="mt-3 text-center">
                        <span class="text-sm font-medium text-gray-700">
                            @switch($currentStep)
                                @case(1) Request Type @break
                                @case(2) Contact Info @break
                                @case(3) Vehicle Details @break
                                @case(4) Financing @break
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>

            <!-- Progress Steps - Desktop Version -->
            <div class="mb-8 hidden lg:block">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Import Financing Application</h2>
                        <span class="text-sm text-gray-500">Step {{ $currentStep }} of 4</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @foreach([['step' => 1, 'label' => 'Request Type'], ['step' => 2, 'label' => 'Contact Info'], ['step' => 3, 'label' => 'Vehicle Details'], ['step' => 4, 'label' => 'Financing']] as $stepInfo)
                            <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                                <button wire:click="goToStep({{ $stepInfo['step'] }})" class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 {{ $currentStep >= $stepInfo['step'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}" @if($stepInfo['step'] > $currentStep) disabled @endif>
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= $stepInfo['step'] ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-500' }}">
                                        @if($currentStep > $stepInfo['step'])
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            {{ $stepInfo['step'] }}
                                        @endif
                                    </div>
                                    <div class="text-left">
                                        <div class="text-sm font-medium">{{ $stepInfo['label'] }}</div>
                                        @if($currentStep === $stepInfo['step'])
                                            <div class="text-xs text-emerald-600">Current step</div>
                                        @elseif($currentStep > $stepInfo['step'])
                                            <div class="text-xs text-emerald-600">Completed</div>
                                        @endif
                                    </div>
                                </button>
                                @if(!$loop->last)
                                    <div class="flex-1 h-px {{ $currentStep > $stepInfo['step'] ? 'bg-emerald-500' : 'bg-gray-300' }} mx-4"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Step 1: Request Type Toggle -->
                @if($currentStep === 1)
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900">What type of financing do you need?</h2>
                            <p class="text-gray-500 text-xs sm:text-sm">Choose the option that best describes your situation.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Buy Car Option -->
                        <button type="button" wire:click="handleRequestTypeClick('buy_car')" class="text-left w-full">
                            <div class="p-4 sm:p-6 rounded-xl sm:rounded-2xl border-2 transition-all duration-300 {{ $requestType === 'buy_car' ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-100' : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50' }}">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 {{ $requestType === 'buy_car' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600' }} transition-colors">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 sm:mb-2">I Want to Buy a Car</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Finance the purchase of a car from abroad. Provide a link to the listing and we'll help you import it.</p>
                                <div class="mt-3 sm:mt-4 flex items-center text-xs sm:text-sm {{ $requestType === 'buy_car' ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    Paste car listing link
                                </div>
                            </div>
                        </button>

                        <!-- Tax & Transport Option -->
                        <button type="button" wire:click="handleRequestTypeClick('tax_transport')" class="text-left w-full">
                            <div class="p-4 sm:p-6 rounded-xl sm:rounded-2xl border-2 transition-all duration-300 {{ $requestType === 'tax_transport' ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-100' : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50' }}">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl flex items-center justify-center mb-3 sm:mb-4 {{ $requestType === 'tax_transport' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600' }} transition-colors">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1 sm:mb-2">Cover Tax & Transport</h3>
                                <p class="text-xs sm:text-sm text-gray-500">Already bought a car? Get financing to cover import taxes, duties, and transport costs.</p>
                                <div class="mt-3 sm:mt-4 flex items-center text-xs sm:text-sm {{ $requestType === 'tax_transport' ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Cover clearing costs
                                </div>
                            </div>
                        </button>
                    </div>
                    
                    @error('requestType')
                        <p class="mt-4 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Step 2: Contact Information -->
                @if($currentStep === 2)
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900">Your Contact Information</h2>
                            <p class="text-gray-500 text-xs sm:text-sm">We'll use this to send you updates and financing offers.</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" wire:model="customerName" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="Enter your full name">
                            @error('customerName') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" wire:model="customerEmail" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="your@email.com">
                            @error('customerEmail') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" wire:model="customerPhone" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="+255 XXX XXX XXX">
                            @error('customerPhone') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 3: Vehicle Details -->
                @if($currentStep === 3)
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900">{{ $requestType === 'buy_car' ? 'Vehicle Details' : 'Car & Cost Details' }}</h2>
                            <p class="text-gray-500 text-xs sm:text-sm">{{ $requestType === 'buy_car' ? 'Paste a link to the car listing or enter details manually.' : 'Tell us about the car and the costs you need to cover.' }}</p>
                        </div>
                    </div>
                    
                    @if($requestType === 'buy_car')
                        <!-- Car Link Section -->
                        <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl sm:rounded-2xl border border-emerald-100">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Car Listing Link
                            </label>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="url" wire:model="carLink" class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="https://www.example.com/car-listing">
                                <button wire:click="extractCarInfo" wire:loading.attr="disabled" class="px-4 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-semibold rounded-lg sm:rounded-xl transition-colors flex items-center justify-center gap-2 text-sm sm:text-base">
                                    <span wire:loading.remove wire:target="extractCarInfo">Extract Info</span>
                                    <span wire:loading wire:target="extractCarInfo">
                                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            @if($extractionError)
                                <p class="mt-2 text-sm text-amber-600">{{ $extractionError }}</p>
                            @endif
                            @if($extractedCarInfo)
                                <div class="mt-3 p-3 bg-white rounded-lg border border-emerald-200">
                                    <p class="text-sm text-emerald-600 font-medium">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Link saved! Please fill in the vehicle details below.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make {{ $requestType === 'buy_car' ? '*' : '' }}</label>
                            <div class="relative">
                                <select wire:model.live="vehicleMake" class="appearance-none w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-white text-sm sm:text-base">
                                    <option value="">Select make</option>
                                    @foreach($vehicleMakes as $makeId => $makeName)
                                        <option value="{{ $makeId }}">{{ $makeName }}</option>
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @error('vehicleMake') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model {{ $requestType === 'buy_car' ? '*' : '' }}</label>
                            <div class="relative">
                                <select wire:model.live="vehicleModel" class="appearance-none w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-white text-sm sm:text-base">
                                    <option value="">Select model</option>
                                    @foreach($this->vehicleModelOptions as $model)
                                        <option value="{{ $model }}">{{ $model }}</option>
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @if(empty($this->vehicleModelOptions))
                                <p class="mt-2 text-xs text-gray-500">Choose a make above to load available models.</p>
                            @endif
                            @error('vehicleModel') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                            <input type="number" wire:model="vehicleYear" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="e.g., 2022" min="1900" max="2030">
                            @error('vehicleYear') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                            <select wire:model="vehicleCondition" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base">
                                <option value="used">Used</option>
                                <option value="new">New</option>
                            </select>
                        </div>

                        @if($requestType === 'buy_car')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Price *</label>
                                <div class="flex gap-2">
                                    <select wire:model.live="vehicleCurrency" class="w-20 sm:w-24 px-2 sm:px-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base">
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="JPY">JPY</option>
                                        <option value="TZS">TZS</option>
                                    </select>
                                    <input type="number" wire:model.live="vehiclePrice" class="flex-1 px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="Enter price" step="0.01">
                                </div>
                                @error('vehiclePrice') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Location</label>
                                <input type="text" wire:model="vehicleLocation" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="e.g., Japan, Dubai">
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Import Tax/Duty Amount *</label>
                                <div class="relative">
                                    <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">TZS</span>
                                    <input type="number" wire:model="taxAmount" class="w-full pl-12 sm:pl-14 pr-3 sm:pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="0.00" step="0.01">
                                </div>
                                @error('taxAmount') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Transport Cost *</label>
                                <div class="relative">
                                    <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">TZS</span>
                                    <input type="number" wire:model="transportCost" class="w-full pl-12 sm:pl-14 pr-3 sm:pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="0.00" step="0.01">
                                </div>
                                @error('transportCost') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Other Clearing Costs</label>
                                <div class="relative">
                                    <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">TZS</span>
                                    <input type="number" wire:model="totalClearingCost" class="w-full pl-12 sm:pl-14 pr-3 sm:pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="0.00" step="0.01">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Step 4: Financing Details -->
                @if($currentStep === 4)
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-4 sm:mb-6">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900">Financing Details</h2>
                            <p class="text-gray-500 text-xs sm:text-sm">Tell us about your financing needs.</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 sm:space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount You Need to Finance *</label>
                            <div class="relative">
                                <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">{{ $requestType === 'buy_car' ? $vehicleCurrency : 'TZS' }}</span>
                                <input type="number" wire:model="financingAmountRequested" class="w-full pl-12 sm:pl-14 pr-3 sm:pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="Enter amount" step="0.01">
                            </div>
                            @error('financingAmountRequested') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                            @if($requestType === 'tax_transport' && ($taxAmount || $transportCost || $totalClearingCost))
                                <p class="mt-2 text-sm text-gray-500">Total costs: TZS {{ number_format(($taxAmount ?? 0) + ($transportCost ?? 0) + ($totalClearingCost ?? 0), 2) }}</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Loan Term *</label>
                            <select wire:model="loanTermMonths" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base">
                                <option value="6">6 months</option>
                                <option value="12">12 months (1 year)</option>
                                <option value="24">24 months (2 years)</option>
                                <option value="36">36 months (3 years)</option>
                                <option value="48">48 months (4 years)</option>
                                <option value="60">60 months (5 years)</option>
                                <option value="72">72 months (6 years)</option>
                                <option value="84">84 months (7 years)</option>
                            </select>
                            @error('loanTermMonths') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Down Payment (if any)</label>
                            <div class="relative">
                                <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">{{ $requestType === 'buy_car' ? $vehicleCurrency : 'TZS' }}</span>
                                <input type="number" wire:model="downPayment" class="w-full pl-12 sm:pl-14 pr-3 sm:pr-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors text-sm sm:text-base" placeholder="0.00" step="0.01">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea wire:model="customerNotes" rows="3" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg sm:rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none text-sm sm:text-base" placeholder="Any additional information you'd like to share..."></textarea>
                        </div>

                        <!-- Summary -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 border border-emerald-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Request Summary</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2 sm:space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs sm:text-sm text-gray-600">Request Type</span>
                                        <span class="px-2 py-1 text-xs font-medium bg-white rounded-md">{{ $requestType === 'buy_car' ? 'Buy Car' : 'Tax & Transport' }}</span>
                                    </div>
                                    @if($this->vehicleMakeName || $vehicleModel)
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs sm:text-sm text-gray-600">Vehicle</span>
                                        <span class="font-medium text-gray-900 text-xs sm:text-sm text-right">{{ $this->vehicleMakeName }} {{ $vehicleModel }} {{ $vehicleYear ? '(' . $vehicleYear . ')' : '' }}</span>
                                    </div>
                                    @endif
                                    @if($requestType === 'buy_car' && $vehiclePrice)
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs sm:text-sm text-gray-600">Vehicle Price</span>
                                        <span class="font-medium text-gray-900 text-xs sm:text-sm">{{ $vehicleCurrency }} {{ number_format($vehiclePrice, 2) }}</span>
                                    </div>
                                    @endif
                                    @if($requestType === 'tax_transport')
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs sm:text-sm text-gray-600">Total Costs</span>
                                        <span class="font-medium text-gray-900 text-xs sm:text-sm">TZS {{ number_format(($taxAmount ?? 0) + ($transportCost ?? 0) + ($totalClearingCost ?? 0), 2) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="bg-white rounded-lg p-3 sm:p-4">
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Financing Amount</div>
                                        <div class="text-xl sm:text-2xl font-bold text-emerald-600">{{ $requestType === 'buy_car' ? $vehicleCurrency : 'TZS' }} {{ number_format($financingAmountRequested ?? 0, 0) }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $loanTermMonths }} months</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 bg-white border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto">
                        @if($currentStep > 1)
                            <button wire:click="previousStep" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm sm:text-base">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span class="hidden sm:inline">Previous</span>
                            </button>
                        @endif
                        <div class="text-xs sm:text-sm text-gray-500">Step {{ $currentStep }} of 4</div>
                    </div>

                    @if($currentStep < 4)
                        <button type="button" wire:click="nextStep" wire:loading.attr="disabled" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors shadow-sm text-sm sm:text-base">
                            <span wire:loading.remove wire:target="nextStep">Next Step</span>
                            <span wire:loading wire:target="nextStep">Loading...</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @endif
                    
                    @if($currentStep === 4)
                        <button 
                            type="button"
                            wire:click.prevent="submit" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 sm:px-8 py-2.5 sm:py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-semibold rounded-lg transition-colors shadow-sm text-sm sm:text-base"
                        >
                            <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Submit Application
                            </span>
                            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Submitting...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
