@extends('layouts.customer')

@section('title', 'Verify OTP - Kibo Auto')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="text-center">
                <img src="{{ asset('logo/green.png') }}" alt="Kibo Auto" class="h-16 mx-auto mb-4">
                <h2 class="text-3xl font-bold text-gray-900">Verify Your Email</h2>
                <p class="mt-2 text-sm text-gray-600">
                    We've sent a 4-digit verification code to<br>
                    @php
                        $email = Auth::user()->email;
                        $parts = explode('@', $email);
                        $local = $parts[0];
                        $domain = $parts[1] ?? '';
                        
                        // Mask local part (keep first and last character)
                        if (strlen($local) > 2) {
                            $maskedLocal = substr($local, 0, 1) . str_repeat('*', strlen($local) - 2) . substr($local, -1);
                        } else {
                            $maskedLocal = str_repeat('*', strlen($local));
                        }
                        
                        // Mask domain part (keep first character)
                        if (strlen($domain) > 1) {
                            $domainParts = explode('.', $domain);
                            $domainName = $domainParts[0];
                            $domainExt = isset($domainParts[1]) ? '.' . $domainParts[1] : '';
                            
                            if (strlen($domainName) > 1) {
                                $maskedDomain = substr($domainName, 0, 1) . str_repeat('*', strlen($domainName) - 1) . $domainExt;
                            } else {
                                $maskedDomain = str_repeat('*', strlen($domainName)) . $domainExt;
                            }
                        } else {
                            $maskedDomain = str_repeat('*', strlen($domain));
                        }
                        
                        $maskedEmail = $maskedLocal . '@' . $maskedDomain;
                    @endphp
                    <span class="font-medium text-gray-900">{{ $maskedEmail }}</span>
                </p>
            </div>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('otp.verify') }}" method="POST">
            @csrf

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

            @if ($errors->any())
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

            <div>
                <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-2 text-center">
                    Enter Verification Code
                </label>
                <div class="flex justify-center gap-3">
                    @for($i = 0; $i < 4; $i++)
                        <input
                            type="text"
                            name="otp_digit_{{$i}}"
                            id="otp_digit_{{$i}}"
                            maxlength="1"
                            pattern="[0-9]"
                            class="w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600"
                            autocomplete="off"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 1) { const next = this.nextElementSibling; if(next && next.tagName === 'INPUT') next.focus(); }"
                            onkeydown="if(event.key === 'Backspace' && !this.value) { const prev = this.previousElementSibling; if(prev && prev.tagName === 'INPUT') prev.focus(); }"
                        >
                    @endfor
                    <input type="hidden" name="otp_code" id="otp_code">
                </div>
                <p class="mt-2 text-xs text-gray-500 text-center">
                    Code expires in 5 minutes
                </p>
            </div>

            <div>
                <button
                    type="submit"
                    class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg font-medium transition-colors"
                >
                    Verify & Continue
                </button>
            </div>

            <div class="flex flex-col gap-3 mt-4">
                <div class="text-center">
                    <form action="{{ route('otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-green-700 hover:text-green-800 font-medium">
                            Resend Code
                        </button>
                    </form>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full border-2 border-gray-300 hover:border-gray-400 text-gray-700 hover:text-gray-900 py-2.5 rounded-lg font-medium transition-colors text-sm">
                        Cancel & Login Again
                    </button>
                </form>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[name^="otp_digit_"]');
    const hiddenInput = document.getElementById('otp_code');
    
    // Update hidden input when any digit changes
    function updateHiddenInput() {
        let code = '';
        inputs.forEach(input => {
            code += input.value || '';
        });
        hiddenInput.value = code;
    }
    
    inputs.forEach(input => {
        input.addEventListener('input', updateHiddenInput);
    });
    
    // Auto-focus first input
    if (inputs.length > 0) {
        inputs[0].focus();
    }
    
    // Handle paste
    document.addEventListener('paste', function(e) {
        e.preventDefault();
        const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 4);
        for (let i = 0; i < pastedData.length && i < inputs.length; i++) {
            inputs[i].value = pastedData[i];
        }
        updateHiddenInput();
        if (pastedData.length < inputs.length) {
            inputs[pastedData.length].focus();
        }
    });
});
</script>
@endsection

