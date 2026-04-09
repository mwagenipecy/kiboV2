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
        const authModal = document.getElementById('authModal');
        
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

        // Watch for auth modal closing - if user is still not logged in, show the prompt again
        if (authModal) {
          const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
              if (mutation.attributeName === 'class') {
                const isHidden = authModal.classList.contains('hidden');
                // If auth modal is closed and user is still a guest, show the login prompt
                if (isHidden && agizaLoginPrompt && !document.body.hasAttribute('data-user-authenticated')) {
                  setTimeout(function() {
                    agizaLoginPrompt.style.display = 'grid';
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
    <div class="text-center mb-8 mt-4">
      <h1 class="text-2xl font-bold text-gray-900 mt-4 " style="font-family: 'Syne', sans-serif;">Import Your Dream Car</h1>
      <p class="mt-1.5 text-sm text-gray-500">Share your listing link and contact details — we'll handle the rest.</p>
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

      {{-- ② LISTING LINK --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900" style="font-family: 'Syne', sans-serif;">Car listing</span>
        </div>

        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Listing URL <span style="color: #009866;">*</span></label>
          <input type="url" wire:model.defer="vehicleLink" required placeholder="https://…" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none transition-all" />
          <p class="text-xs text-gray-500 mt-1">Paste the full link to the vehicle listing. Our team will review it from there.</p>
          @error('vehicleLink') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- SUBMIT --}}
      <div class="p-6 sm:p-8">
        <button type="submit" wire:loading.attr="disabled" class="w-full py-4 rounded-xl text-white font-bold text-base flex items-center justify-center gap-2 transition-all disabled:opacity-50 hover:shadow-lg hover:-translate-y-px" style="background: #009866; font-family: 'Syne', sans-serif; box-shadow: 0 4px 20px rgba(0,152,102,0.35);">
          <span  class="flex" wire:loading.remove>
          <div>Submit Import Request    </div>
          </span>
          <span wire:loading>Processing…</span>
        </button>
      </div>
    </form>
  </div>
  @endauth
</div>
