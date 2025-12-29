<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h3 class="text-lg font-semibold text-gray-900">Vehicle Models</h3>
            <div class="flex gap-3 w-full sm:w-auto">
                <select 
                    wire:model.live="filterMake" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
                    <option value="">All Makes</option>
                    @foreach ($makes as $make)
                        <option value="{{ $make->id }}">{{ $make->name }}</option>
                    @endforeach
                </select>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Search models..."
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                >
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mx-6 mt-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Model Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Make</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($models as $model)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            #{{ $model->id }}
                        </td>
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
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $model->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button 
                                wire:click="edit({{ $model->id }})" 
                                class="text-green-600 hover:text-green-900 mr-3"
                            >
                                Edit
                            </button>
                            <button 
                                wire:click="delete({{ $model->id }})" 
                                onclick="return confirm('Are you sure you want to delete this model?')"
                                class="text-red-600 hover:text-red-900"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">No models found. Create your first model above.</p>
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
</div>
