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
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.enter_password') }}"
                        >
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <span class="ml-2 text-sm text-gray-600">{{ __('auth.remember_me') }}</span>
                        </label>
                        <a href="#" class="text-sm text-green-700 hover:text-green-800">{{ __('auth.forgot_password') }}</a>
                    </div>

                    <!-- Sign In Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors"
                    >
                        {{ __('auth.sign_in') }}
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

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.password') }}</label>
                        <input 
                            type="password" 
                            id="reg_password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.create_password') }}"
                        >
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="reg_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.confirm_password') }}</label>
                        <input 
                            type="password" 
                            id="reg_password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                            placeholder="{{ __('auth.confirm_your_password') }}"
                        >
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
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors"
                    >
                        {{ __('auth.create_account') }}
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
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const authTitle = document.getElementById('authTitle');
    const authSubtitle = document.getElementById('authSubtitle');

    // Auto-open modal if there are validation errors
    @if ($errors->any())
        authModal.classList.remove('hidden');
        setTimeout(() => {
            authPanel.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
        
        // Show registration form if name field was submitted (registration attempt)
        @if (old('name'))
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            authTitle.textContent = '{{ __('auth.register_title') }}';
            authSubtitle.textContent = '{{ __('auth.join_today') }}';
        @endif
    @endif

    // Open modal
    if (openAuthModal) {
        openAuthModal.addEventListener('click', function(e) {
            e.preventDefault();
            authModal.classList.remove('hidden');
            setTimeout(() => {
                authPanel.classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
        });
    }

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
    showRegisterForm.addEventListener('click', function() {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        authTitle.textContent = '{{ __('auth.register_title') }}';
        authSubtitle.textContent = '{{ __('auth.join_today') }}';
    });

    // Switch to login form
    showLoginForm.addEventListener('click', function() {
        registerForm.classList.add('hidden');
        loginForm.classList.remove('hidden');
        authTitle.textContent = '{{ __('auth.auth_title') }}';
        authSubtitle.textContent = '{{ __('auth.welcome_back') }}';
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !authModal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>

