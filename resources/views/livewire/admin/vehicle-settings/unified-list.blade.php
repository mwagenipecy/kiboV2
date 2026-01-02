<div>
    <!-- Tabs and Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 py-4 gap-4">
                <div class="flex flex-wrap gap-3">
                    <button 
                        wire:click="setActiveTab('makes')" 
                        class="px-6 py-3 text-sm font-semibold rounded-lg transition-all shadow-sm border-2 {{ $activeTab === 'makes' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:border-green-500 hover:text-green-600 hover:shadow' }} flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Vehicle Makes
                    </button>
                    <button 
                        wire:click="setActiveTab('models')" 
                        class="px-6 py-3 text-sm font-semibold rounded-lg transition-all shadow-sm border-2 {{ $activeTab === 'models' ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600 shadow-md' : 'bg-white text-gray-700 border-gray-300 hover:border-green-500 hover:text-green-600 hover:shadow' }} flex items-center"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Vehicle Models
                    </button>
                </div>
                
                <div class="flex items-center gap-3">
                    @if ($activeTab === 'makes')
                        <button 
                            wire:click="openMakeModal" 
                            class="px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg flex items-center font-semibold"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Make
                        </button>
                    @else
                        <button 
                            wire:click="openModelModal" 
                            class="px-5 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg flex items-center font-semibold"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Model
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row gap-3">
                @if ($activeTab === 'models')
                    <select 
                        wire:model.live="filterMake" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="">All Makes</option>
                        @foreach ($allMakes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                @endif
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search {{ $activeTab === 'makes' ? 'makes' : 'models' }}..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Makes Table -->
        @if ($activeTab === 'makes')
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Models</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($makes as $make)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $make->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($make->icon)
                                        <img src="{{ asset('storage/' . $make->icon) }}" alt="{{ $make->name }}" class="h-10 w-10 object-contain">
                                    @else
                                        <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $make->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $make->vehicle_models_count }} {{ Str::plural('model', $make->vehicle_models_count) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($make->status === 'active')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $make->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            wire:click="editMake({{ $make->id }})" 
                                            wire:loading.attr="disabled"
                                            wire:target="editMake({{ $make->id }})"
                                            class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed relative"
                                            title="Edit"
                                        >
                                            <svg wire:loading.remove wire:target="editMake({{ $make->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <svg wire:loading wire:target="editMake({{ $make->id }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="confirmDeleteMake({{ $make->id }})" 
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDeleteMake({{ $make->id }})"
                                            class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed relative"
                                            title="Delete"
                                        >
                                            <svg wire:loading.remove wire:target="confirmDeleteMake({{ $make->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <svg wire:loading wire:target="confirmDeleteMake({{ $make->id }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-gray-500 text-sm">No makes found. Click "Add Make" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($makes->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $makes->links() }}
                </div>
            @endif
        @endif

        <!-- Models Table -->
        @if ($activeTab === 'models')
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Make</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($models as $model)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $model->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $model->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($model->vehicleMake->icon)
                                            <img src="{{ asset('storage/' . $model->vehicleMake->icon) }}" alt="{{ $model->vehicleMake->name }}" class="h-8 w-8 object-contain mr-2">
                                        @endif
                                        <span class="text-sm text-gray-900">{{ $model->vehicleMake->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($model->status === 'active')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $model->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button 
                                            wire:click="editModel({{ $model->id }})" 
                                            wire:loading.attr="disabled"
                                            wire:target="editModel({{ $model->id }})"
                                            class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed relative"
                                            title="Edit"
                                        >
                                            <svg wire:loading.remove wire:target="editModel({{ $model->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <svg wire:loading wire:target="editModel({{ $model->id }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                        <button 
                                            wire:click="confirmDeleteModel({{ $model->id }})" 
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDeleteModel({{ $model->id }})"
                                            class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed relative"
                                            title="Delete"
                                        >
                                            <svg wire:loading.remove wire:target="confirmDeleteModel({{ $model->id }})" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <svg wire:loading wire:target="confirmDeleteModel({{ $model->id }})" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-gray-500 text-sm">No models found. Click "Add Model" to create one.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($models->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $models->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Make Modal -->
    @if($showMakeModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Background overlay -->
        <div 
            wire:click="$set('showMakeModal', false)"
            class="fixed inset-0 bg-black/50 bg-opacity-10 transition-opacity"
        ></div>
        
        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full z-50">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $makeId ? 'Edit Make' : 'Create Make' }}</h3>
                </div>

                <form wire:submit="saveMake">
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="makeName" class="block text-sm font-medium text-gray-700 mb-2">
                                Make Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="makeName" 
                                wire:model="makeName" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., Toyota, Honda, BMW"
                            >
                            @error('makeName') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label for="makeIcon" class="block text-sm font-medium text-gray-700 mb-2">
                                Make Icon
                            </label>
                            <input 
                                type="file" 
                                id="makeIcon" 
                                wire:model="makeIcon" 
                                accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                            @error('makeIcon') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                            
                            @if ($makeIcon)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-2">Preview:</p>
                                    <img src="{{ $makeIcon->temporaryUrl() }}" alt="Preview" class="h-16 w-16 object-contain border border-gray-300 rounded-lg p-2">
                                </div>
                            @endif

                            <p class="text-xs text-gray-500 mt-1">Max size: 1MB. Recommended: 100x100px</p>
                        </div>

                        <div>
                            <label for="makeStatus" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="makeStatus" 
                                wire:model="makeStatus" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('makeStatus') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="$set('showMakeModal', false)"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="saveMake"
                            class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                        >
                            <svg wire:loading wire:target="saveMake" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveMake">{{ $makeId ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="saveMake">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Make Delete Modal -->
    @if($showMakeDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Background overlay -->
        <div 
            wire:click="$set('showMakeDeleteModal', false)"
            class="fixed inset-0 bg-black/50 bg-opacity-10 transition-opacity"
        ></div>
        
        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full z-50">
                <div class="px-6 py-4 bg-white">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-0 ml-4 text-left">
                            <h3 class="text-lg font-medium text-gray-900">Delete Make</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this make? This action cannot be undone and will also delete all associated models.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="$set('showMakeDeleteModal', false)"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        wire:click="deleteMake"
                        wire:loading.attr="disabled"
                        wire:target="deleteMake"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <svg wire:loading wire:target="deleteMake" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteMake">Delete</span>
                        <span wire:loading wire:target="deleteMake">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Model Modal -->
    @if($showModelModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Background overlay -->
        <div 
            wire:click="$set('showModelModal', false)"
            class="fixed inset-0 bg-black/50 bg-opacity-10 transition-opacity"
        ></div>
        
        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full z-50">
                <div class="px-6 py-4 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $modelId ? 'Edit Model' : 'Create Model' }}</h3>
                </div>

                <form wire:submit="saveModel">
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label for="modelName" class="block text-sm font-medium text-gray-700 mb-2">
                                Model Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="modelName" 
                                wire:model="modelName" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., Camry, Accord, 3 Series"
                            >
                            @error('modelName') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label for="modelMakeId" class="block text-sm font-medium text-gray-700 mb-2">
                                Make <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="modelMakeId" 
                                wire:model="modelMakeId" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="">Select a make</option>
                                @foreach ($allMakes as $make)
                                    <option value="{{ $make->id }}">{{ $make->name }}</option>
                                @endforeach
                            </select>
                            @error('modelMakeId') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label for="modelStatus" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="modelStatus" 
                                wire:model="modelStatus" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('modelStatus') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="$set('showModelModal', false)"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="saveModel"
                            class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                        >
                            <svg wire:loading wire:target="saveModel" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveModel">{{ $modelId ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="saveModel">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Model Delete Modal -->
    @if($showModelDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Background overlay -->
        <div 
            wire:click="$set('showModelDeleteModal', false)"
            class="fixed inset-0 bg-black/50 bg-opacity-10 transition-opacity"
        ></div>
        
        <!-- Modal content -->
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full z-50">
                <div class="px-6 py-4 bg-white">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-0 ml-4 text-left">
                            <h3 class="text-lg font-medium text-gray-900">Delete Model</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this model? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="$set('showModelDeleteModal', false)"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        wire:click="deleteModel"
                        wire:loading.attr="disabled"
                        wire:target="deleteModel"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                    >
                        <svg wire:loading wire:target="deleteModel" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="deleteModel">Delete</span>
                        <span wire:loading wire:target="deleteModel">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
