<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <div class="flex flex-col gap-2">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autofocus
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400 text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                    {{ __('Confirm') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Or, return to') }}</span>
            <flux:link :href="route('cars.index')" wire:navigate>{{ __('Home') }}</flux:link>
        </div>

        <!-- Trust & Security -->
        <div class="text-center text-sm text-zinc-600 dark:text-zinc-400 space-y-2">
            <p>powered by Savanna Hills</p>
            <p class="text-xs">Copyright © Kibo Auto Limited {{ date('Y') }}.</p>
            <p class="text-xs">Your data is protected with 256-bit SSL encryption</p>
        </div>
    </div>
</x-layouts.auth>
