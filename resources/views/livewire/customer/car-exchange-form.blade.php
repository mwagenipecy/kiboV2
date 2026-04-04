<div class="max-w-3xl mx-auto px-4 py-6 sm:py-8">
    {{-- Guest: login prompt modal --}}
    @guest
        <div id="carExchangeLoginPrompt" role="dialog" aria-modal="true" class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-.53.21-1.04.586-1.414A2 2 0 0114 9a2 2 0 110 4m-2 8a9 9 0 110-18 9 9 0 010 18z"/>
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-bold text-gray-900">Please sign in</h3>
                <p class="mb-6 text-sm text-gray-600">Login is required to submit a car exchange request.</p>
                <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    <button type="button" id="carExchangeLoginBtn" class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-700">Login</button>
                    <button type="button" onclick="window.history.back();" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Back
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carExchangeLoginBtn = document.getElementById('carExchangeLoginBtn');
                const carExchangeLoginPrompt = document.getElementById('carExchangeLoginPrompt');
                const authModal = document.getElementById('authModal');
                
                if (carExchangeLoginBtn) {
                    carExchangeLoginBtn.addEventListener('click', function() {
                        // Hide the car exchange login prompt
                        if (carExchangeLoginPrompt) {
                            carExchangeLoginPrompt.style.display = 'none';
                        }
                        // Trigger the auth modal
                        const openAuthModalBtn = document.getElementById('openAuthModal');
                        if (openAuthModalBtn) {
                            openAuthModalBtn.click();
                        }
                    });
                }

                // Watch for auth modal closing - if user is still not logged in, show the prompt again
                if (authModal) {
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                const isHidden = authModal.classList.contains('hidden');
                                // If auth modal is closed and user is still a guest, show the login prompt
                                if (isHidden && carExchangeLoginPrompt && !document.body.hasAttribute('data-user-authenticated')) {
                                    setTimeout(function() {
                                        carExchangeLoginPrompt.style.display = 'grid';
                                    }, 350);
                                }
                            }
                        });
                    });
                    
                    observer.observe(authModal, { attributes: true });
                }
            });
        </script>
        @endpush
    @endguest

    <!-- Success Message -->
    @if (session()->has('exchange_success'))
        <div class="mb-6 rounded-xl px-4 py-3 text-center" style="background-color: rgba(0, 152, 102, 0.1); color: #007a52;">
            {{ session('exchange_success') }}
        </div>
    @endif

    @auth
    <p class="text-center text-sm text-gray-600 mb-6">Complete the form below — add photos of your car one at a time so dealers can value it accurately.</p>

    <!-- Main Form -->
    <form wire:submit.prevent="submit" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 space-y-8 ring-1 ring-black/5">
        
        <!-- Section 1: Current Vehicle -->
        <div class="space-y-5">
            <h2 class="text-base font-semibold text-gray-900 pb-2 border-b border-gray-200 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-bold text-white shrink-0" style="background-color: #009866;">1</span>
                Your current vehicle
            </h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make *</label>
                    <select 
                        wire:model.live="current_vehicle_make_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Select make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('current_vehicle_make_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model *</label>
                    <select 
                        wire:model.defer="current_vehicle_model_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @if(!$current_vehicle_make_id) disabled @endif
                    >
                        <option value="">@if($current_vehicle_make_id) Select model @else Select make first @endif</option>
                        @foreach($currentModels as $m)
                            <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                        @endforeach
                    </select>
                    @error('current_vehicle_model_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year *</label>
                    <select 
                        wire:model.defer="current_vehicle_year" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Select year</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                    @error('current_vehicle_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number</label>
                    <input 
                        type="text" 
                        wire:model.defer="current_vehicle_registration" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                        placeholder="e.g., T123ABC"
                    >
                    @error('current_vehicle_registration') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mileage (km)</label>
                    <input 
                        type="number" 
                        wire:model.defer="current_vehicle_mileage" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                        placeholder="e.g., 50000"
                    >
                    @error('current_vehicle_mileage') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Condition *</label>
                    <select 
                        wire:model.defer="current_vehicle_condition" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Select condition</option>
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                    @error('current_vehicle_condition') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle Description</label>
                <textarea 
                    wire:model.defer="current_vehicle_description" 
                    rows="3" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="Describe your vehicle's features, any issues, modifications, etc."
                ></textarea>
                @error('current_vehicle_description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vehicle photos</label>
                @if(count($current_vehicle_images) > 0)
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($current_vehicle_images as $imgIdx => $photo)
                        @if($photo)
                        <div wire:key="exchange-photo-{{ $imgIdx }}" class="relative w-24 h-24 rounded-xl overflow-hidden border border-gray-200 bg-gray-100 shrink-0">
                            <img src="{{ $photo->temporaryUrl() }}" alt="" class="w-full h-full object-cover" />
                            <button type="button" wire:click="removeCurrentVehicleImage({{ $imgIdx }})" class="absolute top-1 right-1 rounded-full bg-black/60 text-white p-1 hover:bg-red-600" title="Remove">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
                @if(count($current_vehicle_images) < 12)
                <label class="relative flex flex-col items-center justify-center gap-2 w-full px-4 py-6 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-pointer hover:border-green-600 hover:bg-green-50/50 transition-colors">
                    <input type="file" wire:model="current_vehicle_image_upload" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                    <div wire:loading wire:target="current_vehicle_image_upload" class="absolute inset-0 bg-white/80 flex items-center justify-center rounded-xl text-sm font-medium text-gray-600 z-10">Uploading…</div>
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-sm font-medium text-gray-800">Add a photo</span>
                    <span class="text-xs text-gray-500 text-center px-2">One image per click — up to 12 photos, 5 MB each</span>
                </label>
                @else
                <p class="text-xs text-gray-500">Maximum 12 photos reached.</p>
                @endif
                @error('current_vehicle_image_upload') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @error('current_vehicle_images.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 2: Desired Vehicle -->
        <div class="space-y-5">
            <h2 class="text-base font-semibold text-gray-900 pb-2 border-b border-gray-200 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-bold text-white shrink-0" style="background-color: #009866;">2</span>
                Vehicle you want
            </h2>
            
            <div class="grid md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Make</label>
                    <select 
                        wire:model.live="desired_vehicle_make_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any make</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                    @error('desired_vehicle_make_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                    <select 
                        wire:model.defer="desired_vehicle_model_id" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        @if(!$desired_vehicle_make_id) disabled @endif
                    >
                        <option value="">@if($desired_vehicle_make_id) Any model @else Select make first @endif</option>
                        @foreach($desiredModels as $m)
                            <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                        @endforeach
                    </select>
                    @error('desired_vehicle_model_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Year range</label>
                    <div class="grid grid-cols-2 gap-3">
                        <select 
                            wire:model.defer="desired_min_year" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="">From</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <select 
                            wire:model.defer="desired_max_year" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        >
                            <option value="">To</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('desired_min_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    @error('desired_max_year') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Body type</label>
                    <select 
                        wire:model.defer="desired_body_type" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any type</option>
                        <option value="SUV">SUV</option>
                        <option value="Sedan">Sedan</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Pickup">Pickup</option>
                        <option value="Wagon">Wagon</option>
                        <option value="Coupe">Coupe</option>
                        <option value="Van">Van</option>
                    </select>
                    @error('desired_body_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fuel type</label>
                    <select 
                        wire:model.defer="desired_fuel_type" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any fuel type</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Electric">Electric</option>
                    </select>
                    @error('desired_fuel_type') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
                    <select 
                        wire:model.defer="desired_transmission" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                        <option value="">Any transmission</option>
                        <option value="Automatic">Automatic</option>
                        <option value="Manual">Manual</option>
                    </select>
                    @error('desired_transmission') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Budget</label>
                <input
                    type="text"
                    inputmode="numeric"
                    autocomplete="off"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="e.g., 25,000,000"
                    value="{{ $max_budget }}"
                    x-on:input="
                        (function (el, wire) {
                            const start = el.selectionStart;
                            const before = el.value;
                            const digitsBefore = before.slice(0, start).replace(/\D/g, '').length;
                            const allDigits = before.replace(/\D/g, '');
                            const formatted = allDigits === '' ? '' : Number(allDigits).toLocaleString('en-US');
                            el.value = formatted;
                            let pos = 0;
                            if (digitsBefore === 0) {
                                pos = 0;
                            } else {
                                let seen = 0;
                                for (; pos < formatted.length; pos++) {
                                    if (/\d/.test(formatted[pos])) {
                                        seen++;
                                        if (seen === digitsBefore) {
                                            pos++;
                                            break;
                                        }
                                    }
                                }
                                if (seen < digitsBefore) {
                                    pos = formatted.length;
                                }
                            }
                            el.setSelectionRange(pos, pos);
                            wire.$set('max_budget', formatted);
                        })($event.target, $wire);
                    "
                >
                @error('max_budget') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 3: Additional Info -->
        <div class="space-y-5">
            <h2 class="text-base font-semibold text-gray-900 pb-2 border-b border-gray-200 flex items-center gap-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-bold text-white shrink-0" style="background-color: #009866;">3</span>
                Additional information
            </h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                <input 
                    type="text" 
                    wire:model.defer="location" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="e.g., Dar es Salaam"
                >
                @error('location') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

<div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea 
                    wire:model.defer="notes" 
                    rows="4" 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent" 
                    placeholder="Any additional information about your exchange request..."
                ></textarea>
                @error('notes') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full text-white font-semibold py-3.5 px-6 rounded-xl transition-colors duration-200 text-base disabled:opacity-60" style="background-color: #009866;"
            >
                <span wire:loading.remove wire:target="submit">Submit exchange request</span>
                <span wire:loading wire:target="submit">Submitting…</span>
            </button>
        </div>
    </form>
    @endauth
</div>
