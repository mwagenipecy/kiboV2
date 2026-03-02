<div class="w-full max-w-3xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manual bill / Generate payment link</h1>
        <p class="mt-1 text-sm text-gray-600">Create a bill and either generate a payment link via the API or save manually (no API call). Add one or more items.</p>
    </div>

    @if($message)
        <div class="mb-6 p-4 rounded-lg {{ $success ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800' }}">
            {{ $message }}
            @if($success)
                <a href="{{ route('admin.payment-links.transactions') }}" class="block mt-2 text-sm font-medium text-[#009866] hover:text-[#007a52]">View in Transactions →</a>
            @endif
        </div>
    @endif

    @if(!$success && config('services.universal_payment_link.api_key') === '')
        <div class="mb-6 p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm">
            <strong>API not configured.</strong> Set <code class="bg-amber-100 px-1 rounded">PAYMENT_LINK_API_KEY</code> and <code class="bg-amber-100 px-1 rounded">PAYMENT_LINK_API_SECRET</code> in your <code class="bg-amber-100 px-1 rounded">.env</code> to generate links via the API. You can still <strong>Save manually</strong> to create a bill that appears in the list.
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form wire:submit.prevent class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" wire:model="description" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
                    @error('description')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                    <select wire:model="target" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866]">
                        <option value="individual">Individual</option>
                        <option value="business">Business</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer reference *</label>
                    <input type="text" wire:model="customer_reference" placeholder="e.g. MEMBER2002" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
                    @error('customer_reference')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer name *</label>
                    <input type="text" wire:model="customer_name" placeholder="e.g. Jane Doe" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
                    @error('customer_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer phone *</label>
                    <input type="text" wire:model="customer_phone" placeholder="255712345678" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
                    @error('customer_phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer email *</label>
                    <input type="email" wire:model="customer_email" placeholder="jane@example.com" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866]" />
                    @error('customer_email')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expires at *</label>
                <input type="datetime-local" wire:model="expires_at" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#009866] focus:border-[#009866] max-w-xs" step="1" />
                @error('expires_at')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-700">Items</p>
                    <button type="button" wire:click="addItem" class="text-sm font-medium text-[#009866] hover:text-[#007a52]">+ Add item</button>
                </div>
                @foreach($items as $index => $item)
                <div class="flex flex-wrap items-end gap-3 mb-4 p-3 rounded-lg bg-gray-50 border border-gray-100" wire:key="item-{{ $index }}">
                    <div class="flex-1 min-w-[120px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Reference</label>
                        <input type="text" wire:model="items.{{ $index }}.ref" placeholder="e.g. SHARES_01" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                        @error("items.{$index}.ref")<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
                        <input type="text" wire:model="items.{{ $index }}.name" placeholder="Item name" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                        @error("items.{$index}.name")<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="w-28">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Amount</label>
                        <input type="number" wire:model="items.{{ $index }}.amount" placeholder="0" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                        @error("items.{$index}.amount")<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 text-sm text-gray-600 whitespace-nowrap">
                            <input type="checkbox" wire:model="items.{{ $index }}.allow_partial" class="rounded border-gray-300 text-[#009866] focus:ring-[#009866]" />
                            Partial
                        </label>
                        @if(count($items) > 1)
                        <button type="button" wire:click="removeItem({{ $index }})" class="p-1.5 text-red-600 hover:bg-red-50 rounded" title="Remove item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
                @error('items')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="button" wire:click="generateViaApi" wire:loading.attr="disabled" class="px-4 py-2 rounded-lg text-white font-medium text-sm transition-colors disabled:opacity-50" style="background: linear-gradient(to right, #009866, #007a52);">
                    <span wire:loading.remove wire:target="generateViaApi">Generate via API</span>
                    <span wire:loading wire:target="generateViaApi">Generating…</span>
                </button>
                <button type="button" wire:click="saveManually" wire:loading.attr="disabled" class="px-4 py-2 rounded-lg border-2 border-gray-300 text-gray-700 font-medium text-sm hover:border-[#009866] hover:text-[#009866] transition-colors disabled:opacity-50">
                    <span wire:loading.remove wire:target="saveManually">Save manually (no API)</span>
                    <span wire:loading wire:target="saveManually">Saving…</span>
                </button>
            </div>
        </form>
    </div>
</div>
