<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50">
    <!-- Login Modal -->
    @if($showLoginModal)
        <div class="fixed inset-0 bg-black/50 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Login Required</h2>
                <p class="text-gray-600 mb-6">
                    You need to be logged in to submit an import financing request. Please sign in to continue.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button wire:click="openSideModal('login')" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </button>
                    <button wire:click="openSideModal('register')" class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Create Account
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Side Modal for Login/Register -->
    @if($showSideModal)
        <div class="fixed inset-0 bg-black/50 z-50">
            <!-- Backdrop click to close -->
            <div wire:click="closeSideModal" class="absolute inset-0"></div>

            <!-- Modal Content -->
            <div class="relative ml-auto w-full max-w-md bg-white shadow-2xl transform transition-transform duration-300 ease-in-out h-full overflow-y-auto">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $sideModalType === 'login' ? 'Sign In' : 'Create Account' }}
                    </h2>
                    <button wire:click="closeSideModal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <!-- Success/Error Messages -->
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-green-800">{{ session('message') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-red-800">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if($sideModalType === 'login')
                        <!-- Login Form (matching common login page) -->
                        <div class="text-center mb-6">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-12 w-auto">
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Welcome back</h3>
                            <p class="text-sm text-gray-600">Sign in to access your account</p>
                        </div>

                        <form wire:submit.prevent="login" class="space-y-4">
                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="email"
                                        type="email"
                                        wire:model="loginEmail"
                                        autocomplete="email"
                                        required
                                        value="{{ old('email') }}"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400"
                                        placeholder="you@example.com"
                                    >
                                </div>
                                @error('loginEmail')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="password"
                                        type="password"
                                        wire:model="loginPassword"
                                        autocomplete="current-password"
                                        required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400"
                                        placeholder="Enter your password"
                                    >
                                </div>
                                @error('loginPassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me and Forgot Password -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input
                                        id="remember"
                                        name="remember"
                                        type="checkbox"
                                        class="h-4 w-4 text-green-600 focus:ring-green-600 border-gray-300 rounded"
                                    >
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                                        Remember me
                                    </label>
                                </div>

                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-medium text-green-700 hover:text-green-800">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all"
                                >
                                    <span wire:loading.remove wire:target="login">
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

                        <!-- Divider -->
                        <div class="mt-6">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500">
                                        Or continue with
                                    </span>
                                </div>
                            </div>

                            <!-- Social Login Buttons -->
                            <div class="mt-6 grid grid-cols-2 gap-3">
                                <button type="button" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"/>
                                    </svg>
                                </button>
                                <button type="button" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Back Button -->
                        <div class="mt-6 flex justify-center">
                            <button wire:click="backToInitialModal" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Back
                            </button>
                        </div>
                    @else
                        <!-- Register Form (matching login page style) -->
                        <div class="text-center mb-6">
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('logo/green.png') }}" alt="Logo" class="h-12 w-auto">
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Create an account</h3>
                            <p class="text-sm text-gray-600">Enter your details below to create your account</p>
                        </div>

                        <form wire:submit.prevent="register" class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="name"
                                        type="text"
                                        wire:model="registerName"
                                        autocomplete="name"
                                        required
                                        value="{{ old('name') }}"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400"
                                        placeholder="Enter your full name"
                                    >
                                </div>
                                @error('registerName')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="email"
                                        type="email"
                                        wire:model="registerEmail"
                                        autocomplete="email"
                                        required
                                        value="{{ old('email') }}"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400"
                                        placeholder="you@example.com"
                                    >
                                </div>
                                @error('registerEmail')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="password"
                                        type="password"
                                        wire:model="registerPassword"
                                        autocomplete="new-password"
                                        required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400"
                                        placeholder="Create a password (min 8 characters)"
                                    >
                                </div>
                                @error('registerPassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        wire:model="registerPasswordConfirmation"
                                        autocomplete="new-password"
                                        required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent text-gray-900 placeholder-gray-400"
                                        placeholder="Confirm your password"
                                    >
                                </div>
                                @error('registerPassword')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-all"
                                >
                                    <span wire:loading.remove wire:target="register">
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

                        <!-- Back Button -->
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
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="relative h-64 md:h-72 rounded-2xl overflow-hidden bg-center bg-cover shadow-lg"
                style="background-image: url('{{ asset('image/importFinace.png') }}');"
            >
                <div class="absolute inset-0 bg-black/45"></div>
                <div class="relative h-full flex flex-col md:flex-row items-center justify-between gap-6 px-8 py-8 text-white">
                    <div class="max-w-2xl">
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">Import Car Financing</h1>
                        <p class="text-gray-100 text-sm md:text-base">
                            Get financing for importing your dream car or cover tax and transport costs. Quick approval, competitive rates from multiple lenders.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl px-5 py-3">
                        <svg class="w-10 h-10 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm">
                            <div class="font-semibold">Import Financing</div>
                            <div class="text-green-200 text-xs">Multiple Options</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if($showSuccess)
            <!-- Success Message -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Application Submitted!</h2>
                <p class="text-gray-600 mb-4">Your import financing request has been received.</p>
                <div class="bg-gray-50 rounded-xl p-4 mb-6 inline-block">
                    <span class="text-sm text-gray-500">Reference Number:</span>
                    <div class="text-xl font-bold text-emerald-600">{{ $referenceNumber }}</div>
                </div>
                <p class="text-sm text-gray-500 mb-6">
                    Our team will review your application and you'll receive offers from our partner lenders soon.
                    @auth
                    You can track your request status in <a href="{{ route('import-financing.requests') }}" class="text-emerald-600 hover:underline">My Requests</a>.
                    @endauth
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button wire:click="resetForm" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-colors">
                        Submit Another Request
                    </button>
                    @auth
                    <a href="{{ route('import-financing.requests') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                        View My Requests
                    </a>
                    @endauth
                </div>
            </div>
        @else
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900">Import Financing Application</h2>
                        <span class="text-sm text-gray-500">Step {{ $currentStep }} of 4</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        @foreach([['step' => 1, 'label' => 'Request Type', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'], ['step' => 2, 'label' => 'Contact Info', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'], ['step' => 3, 'label' => 'Vehicle Details', 'icon' => 'M19 9l-7 7-7-7'], ['step' => 4, 'label' => 'Financing', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z']] as $stepInfo)
                            <div class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
                                <button
                                    wire:click="goToStep({{ $stepInfo['step'] }})"
                                    class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 {{ $currentStep >= $stepInfo['step'] ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-50 text-gray-400 hover:bg-gray-100' }}"
                                    @if($stepInfo['step'] > $currentStep) disabled @endif
                                >
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= $stepInfo['step'] ? 'bg-emerald-600 text-white' : 'bg-gray-300 text-gray-500' }}">
                                        @if($currentStep > $stepInfo['step'])
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stepInfo['icon'] }}"/>
                                            </svg>
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
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Step 1: Request Type -->
                @if($currentStep === 1)
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">What type of financing do you need?</h2>
                            <p class="text-gray-500 text-sm">Choose the option that best describes your situation.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Buy Car Option -->
                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live ="requestType" value="buy_car" class="sr-only peer">
                            <div class="p-6 rounded-2xl border-2 transition-all duration-300 {{ $requestType === 'buy_car' ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-100' : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50' }}">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 {{ $requestType === 'buy_car' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-emerald-100 group-hover:text-emerald-600' }} transition-colors">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">I Want to Buy a Car</h3>
                                <p class="text-sm text-gray-500">Finance the purchase of a car from abroad. Provide a link to the listing and we'll help you import it.</p>
                                <div class="mt-4 flex items-center text-sm {{ $requestType === 'buy_car' ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    Paste car listing link
                                </div>
                            </div>
                        </label>

                        <!-- Tax & Transport Option -->
                        <label class="relative cursor-pointer group">
                            <input type="radio" wire:model.live="requestType" value="tax_transport" class="sr-only peer">
                            <div class="p-6 rounded-2xl border-2 transition-all duration-300 {{ $requestType === 'tax_transport' ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-100' : 'border-gray-200 hover:border-emerald-300 hover:bg-gray-50' }}">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 {{ $requestType === 'tax_transport' ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-emerald-100 group-hover:text-emerald-600' }} transition-colors">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cover Tax & Transport</h3>
                                <p class="text-sm text-gray-500">Already bought a car? Get financing to cover import taxes, duties, and transport costs.</p>
                                <div class="mt-4 flex items-center text-sm {{ $requestType === 'tax_transport' ? 'text-emerald-600' : 'text-gray-400' }}">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Cover clearing costs
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    @error('requestType')
                        <p class="mt-4 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Step 2: Contact Information -->
                @if($currentStep === 2)
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Your Contact Information</h2>
                            <p class="text-gray-500 text-sm">We'll use this to send you updates and financing offers.</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input 
                                type="text" 
                                wire:model="customerName"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="Enter your full name"
                            >
                            @error('customerName')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input 
                                type="email" 
                                wire:model="customerEmail"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="your@email.com"
                            >
                            @error('customerEmail')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input 
                                type="tel" 
                                wire:model="customerPhone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="+255 XXX XXX XXX"
                            >
                            @error('customerPhone')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                @endif

                <!-- Step 3: Vehicle Details -->
                @if($currentStep === 3)
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $requestType === 'buy_car' ? 'Vehicle Details' : 'Car & Cost Details' }}</h2>
                            <p class="text-gray-500 text-sm">
                                {{ $requestType === 'buy_car' ? 'Paste a link to the car listing or enter details manually.' : 'Tell us about the car and the costs you need to cover.' }}
                            </p>
                        </div>
                    </div>
                    
                    @if($requestType === 'buy_car')
                        <!-- Car Link Section -->
                        <div class="mb-8 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Car Listing Link
                            </label>
                            <div class="flex gap-3">
                                <input 
                                    type="url" 
                                    wire:model="carLink"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="https://www.example.com/car-listing"
                                >
                                <button 
                                    wire:click="extractCarInfo" 
                                    wire:loading.attr="disabled"
                                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-semibold rounded-xl transition-colors flex items-center gap-2"
                                >
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Make {{ $requestType === 'buy_car' ? '*' : '' }}</label>
                            <div class="relative">
                                <select
                                    wire:model.live="vehicleMake"
                                    class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-white"
                                >
                                    <option value="">Select make</option>
                                    @if(isset($vehicleMakes) && is_array($vehicleMakes))
                                        @foreach($vehicleMakes as $makeId => $makeName)
                                            <option value="{{ $makeId }}">{{ $makeName }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @error('vehicleMake')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Model {{ $requestType === 'buy_car' ? '*' : '' }}</label>
                            <div class="relative">
                                <select
                                    wire:model.live="vehicleModel"
                                    class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors bg-white"
                                >
                                    <option value="">Select model</option>
                                    @if(isset($vehicleModelOptions) && is_array($vehicleModelOptions))
                                        @foreach($vehicleModelOptions as $model)
                                            <option value="{{ $model }}">{{ $model }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                            @if(!isset($vehicleModelOptions) || empty($vehicleModelOptions))
                                <p class="mt-2 text-xs text-gray-500">
                                    Choose a make above to load available models.
                                </p>
                            @endif
                            @error('vehicleModel')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                            <input 
                                type="number" 
                                wire:model="vehicleYear"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="e.g., 2022"
                                min="1900"
                                max="2030"
                            >
                            @error('vehicleYear')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                            <select 
                                wire:model="vehicleCondition"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            >
                                <option value="used">Used</option>
                                <option value="new">New</option>
                            </select>
                        </div>

                        @if($requestType === 'buy_car')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Price *</label>
                                <div class="flex gap-2">
                                    <select 
                                        wire:model="vehicleCurrency"
                                        class="w-24 px-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    >
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                        <option value="GBP">GBP</option>
                                        <option value="JPY">JPY</option>
                                        <option value="TZS">TZS</option>
                                    </select>
                                    <input 
                                        type="number" 
                                        wire:model="vehiclePrice"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                        placeholder="Enter price"
                                        step="0.01"
                                    >
                                </div>
                                @error('vehiclePrice')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Location</label>
                                <input 
                                    type="text" 
                                    wire:model="vehicleLocation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="e.g., Japan, Dubai"
                                >
                            </div>
                        @else
                            <!-- Tax & Transport costs -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Import Tax/Duty Amount *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">TZS</span>
                                    <input 
                                        type="number" 
                                        wire:model="taxAmount"
                                        class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                        placeholder="0.00"
                                        step="0.01"
                                    >
                                </div>
                                @error('taxAmount')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Transport Cost *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">TZS</span>
                                    <input 
                                        type="number" 
                                        wire:model="transportCost"
                                        class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                        placeholder="0.00"
                                        step="0.01"
                                    >
                                </div>
                                @error('transportCost')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Other Clearing Costs</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">TZS</span>
                                    <input 
                                        type="number" 
                                        wire:model="totalClearingCost"
                                        class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                        placeholder="0.00"
                                        step="0.01"
                                    >
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Step 4: Financing Details -->
                @if($currentStep === 4)
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Financing Details</h2>
                            <p class="text-gray-500 text-sm">Tell us about your financing needs.</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount You Need to Finance *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ $requestType === 'buy_car' ? $vehicleCurrency : 'TZS' }}</span>
                                <input 
                                    type="number" 
                                    wire:model="financingAmountRequested"
                                    class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="Enter amount"
                                    step="0.01"
                                >
                            </div>
                            @error('financingAmountRequested')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                            @if($requestType === 'tax_transport' && ($taxAmount || $transportCost || $totalClearingCost))
                                <p class="mt-2 text-sm text-gray-500">
                                    Total costs: TZS {{ number_format(($taxAmount ?? 0) + ($transportCost ?? 0) + ($totalClearingCost ?? 0), 2) }}
                                </p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Loan Term *</label>
                            <select 
                                wire:model="loanTermMonths"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            >
                                <option value="6">6 months</option>
                                <option value="12">12 months (1 year)</option>
                                <option value="24">24 months (2 years)</option>
                                <option value="36">36 months (3 years)</option>
                                <option value="48">48 months (4 years)</option>
                                <option value="60">60 months (5 years)</option>
                                <option value="72">72 months (6 years)</option>
                                <option value="84">84 months (7 years)</option>
                            </select>
                            @error('loanTermMonths')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Down Payment (if any)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">{{ $requestType === 'buy_car' ? $vehicleCurrency : 'TZS' }}</span>
                                <input 
                                    type="number" 
                                    wire:model="downPayment"
                                    class="w-full pl-14 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                    placeholder="0.00"
                                    step="0.01"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea 
                                wire:model="customerNotes"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                                placeholder="Any additional information you'd like to share..."
                            ></textarea>
                        </div>

                        <!-- Summary -->
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-6 border border-emerald-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900">Request Summary</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Request Type</span>
                                        <span class="px-2 py-1 text-xs font-medium bg-white rounded-md">{{ $requestType === 'buy_car' ? 'Buy Car' : 'Tax & Transport' }}</span>
                                    </div>
                                    @if($vehicleMake || $vehicleModel)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Vehicle</span>
                                        <span class="font-medium text-gray-900">{{ $vehicleMake }} {{ $vehicleModel }} {{ $vehicleYear ? '(' . $vehicleYear . ')' : '' }}</span>
                                    </div>
                                    @endif
                                    @if($requestType === 'buy_car' && $vehiclePrice)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Vehicle Price</span>
                                        <span class="font-medium text-gray-900">{{ $vehicleCurrency }} {{ number_format($vehiclePrice, 2) }}</span>
                                    </div>
                                    @endif
                                    @if($requestType === 'tax_transport')
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Total Costs</span>
                                        <span class="font-medium text-gray-900">TZS {{ number_format(($taxAmount ?? 0) + ($transportCost ?? 0) + ($totalClearingCost ?? 0), 2) }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <div class="text-center">
                                        <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Financing Amount</div>
                                        <div class="text-2xl font-bold text-emerald-600">{{ $requestType === 'buy_car' ? $vehicleCurrency : 'TZS' }} {{ number_format($financingAmountRequested ?? 0, 0) }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $loanTermMonths }} months</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="px-8 py-6 bg-white border-t border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        @if($currentStep > 1)
                            <button
                                wire:click="previousStep"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Previous
                            </button>
                        @endif

                        <div class="text-sm text-gray-500">
                            Step {{ $currentStep }} of 4
                        </div>
                    </div>

                    @if($currentStep < 4)
                        <button
                            wire:click="nextStep"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors shadow-sm"
                        >
                            Next Step
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    @else
                        <button
                            wire:click="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-400 text-white font-semibold rounded-lg transition-colors shadow-sm"
                        >
                            <span wire:loading.remove wire:target="submit">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    @endauth
</div>

