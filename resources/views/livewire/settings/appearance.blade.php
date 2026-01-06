<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <!-- Page Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Appearance</h2>
        <p class="mt-2 text-gray-600">Update the appearance settings for your account</p>
    </div>

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-4">
                Theme Preference
            </label>
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
            <p class="mt-3 text-sm text-gray-600">
                Choose how the application looks to you. Select a single theme, or sync with your system and automatically switch between day and night themes.
            </p>
        </div>
    </div>
</div>
