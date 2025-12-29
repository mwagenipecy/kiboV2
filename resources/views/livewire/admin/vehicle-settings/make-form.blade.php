<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        {{ $editingId ? 'Edit Make' : 'Create Make' }}
    </h3>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-4">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Make Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                wire:model="name" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="e.g., Toyota, Honda, BMW"
            >
            @error('name') 
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Icon Upload -->
        <div>
            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                Make Icon
            </label>
            <input 
                type="file" 
                id="icon" 
                wire:model="icon" 
                accept="image/*"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
            @error('icon') 
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
            @enderror
            
            @if ($icon)
                <div class="mt-2">
                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                    <img src="{{ $icon->temporaryUrl() }}" alt="Preview" class="h-16 w-16 object-contain border border-gray-300 rounded-lg p-2">
                </div>
            @endif

            <p class="text-xs text-gray-500 mt-1">Max size: 1MB. Recommended: 100x100px</p>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
            </label>
            <select 
                id="status" 
                wire:model="status" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            @error('status') 
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
            <button 
                type="submit" 
                class="px-6 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm"
            >
                {{ $editingId ? 'Update Make' : 'Create Make' }}
            </button>

            @if ($editingId)
                <button 
                    type="button" 
                    wire:click="resetForm"
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all"
                >
                    Cancel
                </button>
            @endif
        </div>
    </form>
</div>
