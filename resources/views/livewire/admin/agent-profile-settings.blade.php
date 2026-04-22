<div>
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Shop Name</label>
                <input id="name" type="text" wire:model="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="companyName" class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                <input id="companyName" type="text" wire:model="companyName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('companyName')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email (read only)</label>
                <input type="text" value="{{ $email }}" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number (read only)</label>
                <input type="text" value="{{ $phoneNumber }}" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-600">
            </div>
        </div>

        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea id="address" rows="3" wire:model="address" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                <input id="latitude" type="number" step="any" wire:model="latitude" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('latitude')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                <input id="longitude" type="number" step="any" wire:model="longitude" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                @error('longitude')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>
