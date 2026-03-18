@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@600;700&display=swap" rel="stylesheet">
@endpush

<div class="agiza-import-kibo" style="font-family: 'DM Sans', sans-serif; min-height: 60vh; color: #1a1a1a;">
  {{-- Guest: login prompt --}}
  @guest
    <div id="agizaLoginPrompt" role="dialog" aria-modal="true" class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4">
      <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
          <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-.53.21-1.04.586-1.414A2 2 0 0114 9a2 2 0 110 4m-2 8a9 9 0 110-18 9 9 0 010 18z"/>
          </svg>
        </div>
        <h3 class="mb-2 text-xl font-bold text-gray-900" style="font-family: 'Syne', sans-serif;">Please sign in</h3>
        <p class="mb-6 text-sm text-gray-600">Login is required to submit an import request.</p>
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
          <button type="button" id="agizaLoginBtn" class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-700">Login</button>
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
        const agizaLoginBtn = document.getElementById('agizaLoginBtn');
        const agizaLoginPrompt = document.getElementById('agizaLoginPrompt');
        
        if (agizaLoginBtn) {
          agizaLoginBtn.addEventListener('click', function() {
            // Hide the Agiza login prompt
            if (agizaLoginPrompt) {
              agizaLoginPrompt.style.display = 'none';
            }
            // Trigger the auth modal
            const openAuthModalBtn = document.getElementById('openAuthModal');
            if (openAuthModalBtn) {
              openAuthModalBtn.click();
            }
          });
        }
      });
    </script>
    @endpush
  @endguest

  @auth
  {{-- Success Modal --}}
  @if($showSuccessModal)
  <div class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4" wire:click.self="closeSuccessModal">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl" wire:click.stop>
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
      </div>
      <h3 class="mb-1 text-xl font-bold text-gray-900" style="font-family: 'Syne', sans-serif;">Request Submitted!</h3>
      <p class="mb-4 text-sm text-gray-500">{{ $successMessage }}</p>
      <div class="mb-4 rounded-xl bg-emerald-50 px-3 py-2">
        <span class="text-sm text-gray-600">Request Number</span><br>
        <span class="text-sm font-bold text-emerald-700" style="font-family: 'Syne', sans-serif;">{{ $requestNumber }}</span>
      </div>
      <p class="mb-4 text-xs text-gray-500">Track your request in <strong>My Requests</strong></p>
      <div class="flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('agiza-import.requests') }}" class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-center text-sm font-semibold text-white hover:bg-emerald-700">View My Requests</a>
        <button wire:click="closeSuccessModal" class="flex-1 rounded-xl border border-gray-300 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">Close</button>
      </div>
    </div>
  </div>
  @endif

  {{-- Error Modal --}}
  @if($showErrorModal)
  <div class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4" wire:click.self="closeErrorModal">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 text-center shadow-2xl" wire:click.stop>
      <p class="mb-4 text-red-600">{{ $errorMessage }}</p>
      <button wire:click="closeErrorModal" class="rounded-xl border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50">Close</button>
    </div>
  </div>
  @endif

  <div class="max-w-[700px] mx-auto">
    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold text-gray-900" style="font-family: 'Syne', sans-serif;">Import Your Dream Car</h1>
      <p class="mt-1.5 text-sm text-gray-500">We'll help you bring your vehicle to Tanzania — hassle-free.</p>
    </div>

    <form wire:submit.prevent="submit" class="bg-white rounded-2xl shadow-sm overflow-hidden" style="box-shadow: 0 2px 16px rgba(0,0,0,0.06);">
      
      {{-- ① CONTACT --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900" style="font-family: 'Syne', sans-serif;">Contact Information</span>
        </div>
        <div class="flex items-center gap-3 rounded-xl py-3 px-4 mb-4" style="background: rgba(0,152,102,0.08);">
          <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0" style="background: #009866;">{{ strtoupper(substr($customerName, 0, 2)) }}</div>
          <div>
            <p class="text-sm font-medium" style="color: #007a52;">{{ $customerName }}</p>
            <span class="text-xs text-gray-500">{{ $customerEmail }}</span>
          </div>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Phone Number <span style="color: #009866;">*</span></label>
          <input type="tel" wire:model.defer="customerPhone" required inputmode="numeric" pattern="^0\d{9}$" maxlength="10" placeholder="0712345678" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none transition-all" />
          @error('customerPhone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- ② REQUEST TYPE --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900" style="font-family: 'Syne', sans-serif;">Request Type</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div wire:click="$set('requestType', 'with_link')" class="relative cursor-pointer p-4 rounded-xl border-2 transition-all {{ $requestType === 'with_link' ? 'border-[#009866] bg-[rgba(0,152,102,0.08)]' : 'border-gray-200' }}">
            <div class="flex items-center gap-2 mb-2">
              <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all {{ $requestType === 'with_link' ? 'border-[#009866] bg-[#009866]' : 'border-gray-300' }}">
                @if($requestType === 'with_link')
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                @endif
              </div>
              <span class="text-sm font-semibold text-gray-900">I have a car link</span>
            </div>
            <p class="text-xs text-gray-500">Provide a link to the car listing online</p>
          </div>
          <div wire:click="$set('requestType', 'already_contacted')" class="relative cursor-pointer p-4 rounded-xl border-2 transition-all {{ $requestType === 'already_contacted' ? 'border-[#009866] bg-[rgba(0,152,102,0.08)]' : 'border-gray-200' }}">
            <div class="flex items-center gap-2 mb-2">
              <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all {{ $requestType === 'already_contacted' ? 'border-[#009866] bg-[#009866]' : 'border-gray-300' }}">
                @if($requestType === 'already_contacted')
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                @endif
              </div>
              <span class="text-sm font-semibold text-gray-900">Already contacted dealer</span>
            </div>
            <p class="text-xs text-gray-500">You've spoken to a dealer and need import help</p>
          </div>
        </div>
      </div>

      {{-- ③ VEHICLE DETAILS --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900" style="font-family: 'Syne', sans-serif;">Vehicle Details</span>
        </div>

        <div class="mb-4 {{ $requestType === 'with_link' ? '' : 'hidden' }}">
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Car Listing Link <span style="color: #009866;">*</span></label>
          <input type="url" wire:model.lazy="vehicleLink" {{ $requestType === 'with_link' ? 'required' : '' }} placeholder="https://example.com/car-listing" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none transition-all" />
          <p class="text-xs text-gray-500 mt-1">Paste the link to the car you want to import</p>
          @error('vehicleLink') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4 {{ $requestType === 'already_contacted' ? '' : 'hidden' }}">
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Dealer Contact Information <span style="color: #009866;">*</span></label>
          <textarea wire:model.lazy="dealerContactInfo" {{ $requestType === 'already_contacted' ? 'required' : '' }} rows="3" placeholder="Dealer name, phone, email, location..." class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none resize-none leading-relaxed"></textarea>
          @error('dealerContactInfo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Make <span style="color: #009866;">*</span></label>
            <select wire:model.live="vehicleMakeId" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="">Select Make</option>
              @foreach($vehicleMakes as $make)
              <option value="{{ $make->id }}">{{ $make->name }}</option>
              @endforeach
            </select>
            @error('vehicleMakeId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Model <span style="color: #009866;">*</span></label>
            <select wire:model.live="vehicleModelId" required {{ !$vehicleMakeId ? 'disabled' : '' }} class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="">{{ $vehicleMakeId ? 'Select Model' : 'Select Make First' }}</option>
              @foreach($vehicleModels as $model)
              <option value="{{ $model->id }}">{{ $model->name }}</option>
              @endforeach
            </select>
            @error('vehicleModelId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Year</label>
            <input type="number" wire:model.lazy="vehicleYear" min="1990" max="2026" placeholder="2020" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem]" />
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Condition</label>
            <select wire:model.defer="vehicleCondition" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="new">New</option>
              <option value="used">Used</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Source Country <span style="color: #009866;">*</span></label>
            <select wire:model.defer="sourceCountry" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="">Select Country</option>
              @foreach($countries as $country)
              <option value="{{ $country }}">{{ $country }}</option>
              @endforeach
            </select>
            @error('sourceCountry') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Estimated Price</label>
            <input type="number" wire:model.lazy="estimatedPrice" min="0" step="0.01" placeholder="50000" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem]" />
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Currency</label>
            <select wire:model.defer="priceCurrency" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="USD">USD</option>
              <option value="EUR">EUR</option>
              <option value="GBP">GBP</option>
              <option value="JPY">JPY</option>
              <option value="TZS">TZS</option>
            </select>
          </div>
        </div>
      </div>

      {{-- ④ ADDITIONAL INFO --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900" style="font-family: 'Syne', sans-serif;">Additional Information</span>
        </div>

        <div class="mb-4">
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Special Requirements</label>
          <textarea wire:model.lazy="specialRequirements" rows="2" placeholder="e.g. Need specific color, modifications, inspection before shipping..." class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none resize-none leading-relaxed"></textarea>
        </div>

        <div class="mb-4">
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Additional Notes</label>
          <textarea wire:model.lazy="customerNotes" rows="3" placeholder="Any other information that might help us assist you better..." class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none resize-none leading-relaxed"></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Vehicle Photos (Optional)</label>
            <div class="flex items-center gap-2.5 p-3 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 hover:border-[#009866] hover:bg-[rgba(0,152,102,0.08)] transition-all">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
              </div>
              <div class="flex-1">
                <input type="file" wire:model="vehicleImages" multiple accept="image/*" id="vehicleImagesInput" class="block w-full text-sm text-gray-900 cursor-pointer" />
              </div>
            </div>
            <div wire:loading wire:target="vehicleImages" class="mt-2 text-xs text-blue-600">Uploading photos...</div>
            @if(!empty($vehicleImages))
            <div class="mt-2 text-xs text-green-600">{{ count($vehicleImages) }} photo(s) selected</div>
            @endif
            @error('vehicleImages') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            @error('vehicleImages.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Documents (Optional)</label>
            <div class="flex items-center gap-2.5 p-3 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 hover:border-[#009866] hover:bg-[rgba(0,152,102,0.08)] transition-all">
              <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
                <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
              </div>
              <div class="flex-1">
                <input type="file" wire:model="documents" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" id="documentsInput" class="block w-full text-sm text-gray-900 cursor-pointer" />
              </div>
            </div>
            <div wire:loading wire:target="documents" class="mt-2 text-xs text-blue-600">Uploading documents...</div>
            @if(!empty($documents))
            <div class="mt-2 text-xs text-green-600">{{ count($documents) }} document(s) selected</div>
            @endif
            @error('documents') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            @error('documents.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="mt-4 rounded-r-lg py-2.5 px-4 text-sm leading-relaxed" style="background: rgba(0,152,102,0.08); border-left: 3px solid #009866; color: #007a52;">
          💡 <strong>Tip:</strong> More details help us provide an accurate quote faster.
        </div>
      </div>

      {{-- SUBMIT --}}
      <div class="p-6 sm:p-8">
        <button type="submit" wire:loading.attr="disabled" class="w-full py-4 rounded-xl text-white font-bold text-base flex items-center justify-center gap-2 transition-all disabled:opacity-50 hover:shadow-lg hover:-translate-y-px" style="background: #009866; font-family: 'Syne', sans-serif; box-shadow: 0 4px 20px rgba(0,152,102,0.35);">
          <span wire:loading.remove>
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
            Submit Import Request
          </span>
          <span wire:loading>Processing…</span>
        </button>
      </div>
    </form>
  </div>
  @endauth
</div>
