<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Pricing Management</h1>
        <p class="mt-2 text-gray-600">Manage advertising pricing plans for cars, trucks, and garage services</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <div class="flex gap-2">
            <button wire:click="openModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Plan
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Features</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($plans as $plan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                                    @if($plan->is_popular)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Popular</span>
                                    @endif
                                    @if($plan->is_featured)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Featured</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">{{ $plan->category }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-semibold">{{ $plan->currency }} {{ number_format($plan->price, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $plan->duration_days ? $plan->duration_days . ' days' : 'One-time' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleActive({{ $plan->id }})" class="px-2 py-1 text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">
                                {{ count($plan->features ?? []) }} features
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="openModal({{ $plan->id }})" class="text-green-600 hover:text-green-900 mr-3">Edit</button>
                            <button wire:click="delete({{ $plan->id }})" wire:confirm="Are you sure you want to delete this plan?" class="text-red-600 hover:text-red-900">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No pricing plans found. Create your first plan!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $plans->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black/50 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $editingId ? 'Edit Pricing Plan' : 'Create New Pricing Plan' }}
                    </h3>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Plan Name *</label>
                                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category *</label>
                                <select wire:model="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="cars">Cars</option>
                                    <option value="trucks">Trucks</option>
                                    <option value="garage">Garage</option>
                                </select>
                                @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Price *</label>
                                <input type="number" step="0.01" wire:model="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Currency *</label>
                                <input type="text" wire:model="currency" maxlength="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                @error('currency') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Duration (Days)</label>
                                <input type="number" wire:model="durationDays" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <p class="text-xs text-gray-500 mt-1">Leave empty for one-time payment</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isFeatured" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <label class="ml-2 text-sm text-gray-700">Featured</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isPopular" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <label class="ml-2 text-sm text-gray-700">Popular</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" wire:model="isActive" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <label class="ml-2 text-sm text-gray-700">Active</label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                            <input type="number" wire:model="sortOrder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Features</label>
                            <div class="flex gap-2 mb-2">
                                <input type="text" wire:model="newFeature" wire:keydown.enter.prevent="addFeature" placeholder="Add a feature" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <button type="button" wire:click="addFeature" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-md text-sm">Add</button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($features as $index => $feature)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                        {{ $feature }}
                                        <button type="button" wire:click="removeFeature({{ $index }})" class="ml-2 text-green-600 hover:text-green-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
