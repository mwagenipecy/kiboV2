<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('status', 'Password updated successfully!');
        $this->dispatch('password-updated');
    }
}; ?>

<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Update Password</h2>
        <p class="mt-2 text-gray-600">Ensure your account is using a long, random password to stay secure</p>
    </div>

    <!-- Success Message -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="updatePassword" class="space-y-6">
        <!-- Current Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Current Password
            </label>
            <input 
                type="password" 
                wire:model="current_password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                required
            >
            @error('current_password') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                New Password
            </label>
            <input 
                type="password" 
                wire:model="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                required
            >
            @error('password') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm New Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Confirm New Password
            </label>
            <input 
                type="password" 
                wire:model="password_confirmation"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                required
            >
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end pt-4 border-t">
            <button 
                type="submit"
                class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors"
            >
                Update Password
            </button>
        </div>
    </form>
</div>
