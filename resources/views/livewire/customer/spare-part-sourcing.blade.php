<div class="min-h-[60vh] text-gray-900">
  @if(!$this->canAccessSourcing())
    <div id="sparePartsLoginPrompt" role="dialog" aria-modal="true" aria-labelledby="login-prompt-title" class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4">
      <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl">
        @if($guestModalStep === 'choose')
          <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
            <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-.53.21-1.04.586-1.414A2 2 0 0114 9a2 2 0 110 4m-2 8a9 9 0 110-18 9 9 0 010 18z"/>
            </svg>
          </div>
          @if($offerGuestPhoneOtp)
            <h3 id="login-prompt-title" class="mb-2 text-xl font-bold text-gray-900">Continue to order</h3>
            <p class="mb-6 text-sm text-gray-600">Sign in with your account, or verify your phone once to place an order. We’ll text you tracking links after you submit.</p>
            <div class="flex flex-col gap-3">
              <button
                type="button"
                class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-700"
                onclick="document.getElementById('sparePartsLoginPrompt')?.classList.add('hidden'); Livewire.dispatch('open-auth-modal'); const o=document.getElementById('openAuthModal'); if(o) o.click();"
              >
                Login with account
              </button>
              <button type="button" wire:click="startGuestPhoneFlow" class="rounded-xl border-2 border-emerald-600 px-5 py-2.5 font-semibold text-emerald-700 hover:bg-emerald-50">
                One-time sign-in (phone + OTP)
              </button>
              <button
                type="button"
                onclick="window.history.back();"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
              </button>
            </div>
          @else
            <h3 id="login-prompt-title" class="mb-2 text-xl font-bold text-gray-900">Please sign in</h3>
            <p class="mb-6 text-sm text-gray-600">Login is required to submit a spare parts request.</p>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
              <button
                type="button"
                class="rounded-xl bg-emerald-600 px-5 py-2.5 font-semibold text-white hover:bg-emerald-700"
                onclick="document.getElementById('sparePartsLoginPrompt')?.classList.add('hidden'); Livewire.dispatch('open-auth-modal'); const o=document.getElementById('openAuthModal'); if(o) o.click();"
              >
                Login
              </button>
              <button
                type="button"
                onclick="window.history.back();"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50"
              >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
              </button>
            </div>
          @endif
        @elseif($guestModalStep === 'phone')
          <h3 class="mb-2 text-xl font-bold text-gray-900">Your phone number</h3>
          <p class="mb-4 text-sm text-gray-600">We’ll send a 4-digit code by SMS. Standard rates may apply.</p>
          <input
            type="tel"
            wire:model="guestOtpPhone"
            inputmode="numeric"
            maxlength="10"
            placeholder="0712345678"
            class="mb-3 w-full rounded-xl border border-gray-300 px-4 py-3 text-center text-lg tracking-wide focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"
          />
          @error('guestOtpPhone') <p class="mb-3 text-sm text-red-600">{{ $message }}</p> @enderror
          <button type="button" wire:click="sendSparePartsGuestOtp" wire:loading.attr="disabled" class="mb-3 w-full rounded-xl bg-emerald-600 py-2.5 font-semibold text-white hover:bg-emerald-700 disabled:opacity-50">
            <span wire:loading.remove wire:target="sendSparePartsGuestOtp">Send code</span>
            <span wire:loading wire:target="sendSparePartsGuestOtp">Sending…</span>
          </button>
          <button type="button" wire:click="backToGuestChoose" class="text-sm text-gray-600 hover:text-gray-900">← Other options</button>
        @elseif($guestModalStep === 'otp')
          <h3 class="mb-2 text-xl font-bold text-gray-900">Enter the code</h3>
          <p class="mb-4 text-sm text-gray-600">Sent to <span class="font-medium text-gray-900">{{ $guestOtpPhone }}</span></p>
          <input
            type="text"
            wire:model="guestOtpCode"
            inputmode="numeric"
            maxlength="4"
            placeholder="0000"
            class="mb-3 w-full rounded-xl border border-gray-300 px-4 py-3 text-center text-2xl font-bold tracking-[0.5em] focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/30"
          />
          @error('guestOtpCode') <p class="mb-3 text-sm text-red-600">{{ $message }}</p> @enderror
          <button type="button" wire:click="verifySparePartsGuestOtp" wire:loading.attr="disabled" class="mb-3 w-full rounded-xl bg-emerald-600 py-2.5 font-semibold text-white hover:bg-emerald-700 disabled:opacity-50">
            <span wire:loading.remove wire:target="verifySparePartsGuestOtp">Verify & continue</span>
            <span wire:loading wire:target="verifySparePartsGuestOtp">Checking…</span>
          </button>
          <div class="flex flex-col gap-2 text-sm">
            <button type="button" wire:click="sendSparePartsGuestOtp" class="text-emerald-700 font-medium hover:underline">Resend code</button>
            <button type="button" wire:click="backToGuestChoose" class="text-gray-600 hover:text-gray-900">← Change number</button>
          </div>
        @endif
      </div>
    </div>
    <script>
      document.addEventListener('livewire:init', () => {
        const prompt = () => document.getElementById('sparePartsLoginPrompt');
        const authModal = () => document.getElementById('authModal');

        Livewire.on('open-auth-modal', () => {
          prompt()?.classList.add('hidden');
          const m = authModal();
          const p = document.getElementById('authPanel');
          if (m && p) {
            m.classList.remove('hidden');
            setTimeout(() => p.classList.remove('translate-x-full'), 10);
            document.body.style.overflow = 'hidden';
          }
        });

        const setupObserver = () => {
          const m = authModal();
          if (!m) return;

          const obs = new MutationObserver(() => {
            const isHidden = m.classList.contains('hidden');
            if (isHidden) {
              prompt()?.classList.remove('hidden');
            }
          });

          obs.observe(m, { attributes: true, attributeFilter: ['class'] });
        };

        setupObserver();
      });
    </script>
  @endif

  @if($this->canAccessSourcing())
  {{-- Success Modal (Kibo style) --}}
  @if($showSuccessModal)
  <div class="fixed inset-0 z-[9999] grid place-items-center bg-black/50 p-4" wire:click.self="closeSuccessModal">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl" wire:click.stop>
      <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50">
        <svg class="h-7 w-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
      </div>
      <h3 class="mb-1 text-xl font-bold text-gray-900">{{ count($createdOrderNumbers) > 1 ? count($createdOrderNumbers) . ' Orders Submitted!' : 'Request Submitted!' }}</h3>
      <p class="mb-4 text-sm text-gray-500">{{ $successMessage }}</p>
      <div class="mb-4 space-y-2 text-left">
        @foreach($createdOrderNumbers as $orderNumber)
        <div class="flex items-center justify-between rounded-xl bg-emerald-50 px-3 py-2">
          <span class="text-sm text-gray-600 truncate max-w-[55%]">Order</span>
          <span class="text-sm font-bold whitespace-nowrap text-emerald-700">{{ $orderNumber }}</span>
        </div>
        @endforeach
      </div>
      @auth
        <p class="mb-4 text-xs text-gray-500">Track your orders in <strong>My Orders</strong></p>
        <div class="flex flex-col gap-3 sm:flex-row">
          <a href="{{ route('spare-parts.orders') }}" class="flex-1 rounded-xl bg-emerald-600 py-2.5 text-center text-sm font-semibold text-white hover:bg-emerald-700">View My Orders</a>
          <button wire:click="closeSuccessModal" type="button" class="flex-1 rounded-xl border border-gray-300 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">Close</button>
        </div>
      @else
        <p class="mb-4 text-xs text-gray-500">We sent tracking link(s) to your phone. You can also open them below.</p>
        <div class="mb-4 space-y-2">
          @foreach($createdOrderTrackLinks as $link)
            <a href="{{ $link['url'] }}" class="flex w-full items-center justify-center rounded-xl bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Track {{ $link['number'] }}</a>
          @endforeach
        </div>
        <button wire:click="closeSuccessModal" type="button" class="w-full rounded-xl border border-gray-300 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">Close</button>
      @endauth
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

  <div class="max-w-full mx-auto">
    <form wire:submit.prevent="submitOrders" id="sparePartsSourcingForm" class="bg-white rounded-2xl shadow-sm overflow-hidden" style="box-shadow: 0 2px 16px rgba(0,0,0,0.06);">
      {{-- ① CONTACT --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900">Contact</span>
        </div>
        @auth
          <div class="flex items-center gap-3 rounded-xl py-3 px-4 mb-4" style="background: rgba(0,152,102,0.08);">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0" style="background: #009866;">{{ strtoupper(substr($customerName, 0, 2)) }}</div>
            <div>
              <p class="text-sm font-medium" style="color: #007a52;">{{ $customerName }}</p>
              <span class="text-xs text-gray-500">{{ $customerEmail }}</span>
            </div>
          </div>
        @else
          <div class="mb-4 space-y-3">
            <div>
              <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Full name <span style="color: #009866;">*</span></label>
              <input type="text" wire:model.defer="customerName" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none" placeholder="Your name" />
              @error('customerName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Email <span style="color: #009866;">*</span></label>
              <input type="email" wire:model.defer="customerEmail" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none" placeholder="you@example.com" />
              @error('customerEmail') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
          </div>
        @endauth
        <div class="mb-0">
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Phone Number <span style="color: #009866;">*</span></label>
          @php
            $phoneReadonly = $guestAccessVerified && ! Auth::check();
            $phoneInputClass = $phoneReadonly
              ? 'w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-700 text-[0.95rem] outline-none cursor-not-allowed'
              : 'w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 text-[0.95rem] focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none transition-all';
          @endphp
          <input
            type="tel"
            wire:model.defer="customerPhone"
            required
            inputmode="numeric"
            pattern="^0\d{9}$"
            maxlength="10"
            placeholder="0712345678"
            @if($phoneReadonly) readonly @endif
            class="{{ $phoneInputClass }}"
          />
          @if($guestAccessVerified && !Auth::check())
            <p class="text-xs text-gray-500 mt-1">Verified with one-time sign-in. To use a different number, refresh the page and verify again.</p>
          @endif
          <p class="text-xs text-gray-500 mt-1">Used by the supplier to reach you if needed.</p>
          @error('customerPhone') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- ② VEHICLE --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900">Vehicle</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Make <span style="color: #009866;">*</span></label>
            <select wire:model.live="vehicleMakeId" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="">Select Make</option>
              @foreach($vehicleMakes as $make) <option value="{{ $make->id }}">{{ $make->name }}</option> @endforeach
            </select>
            @error('vehicleMakeId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Model <span style="color: #009866;">*</span></label>
            <select wire:model.live="vehicleModelId" required @if(!$vehicleMakeId) disabled @endif class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="">@if($vehicleMakeId) Select Model @else Select Make First @endif</option>
              @foreach($vehicleModels as $model) <option value="{{ $model->id }}">{{ $model->name }}</option> @endforeach
            </select>
            @error('vehicleModelId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      {{-- ③ PARTS --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900">Parts Requested</span>
          <span class="ml-auto text-xs font-bold text-white px-2.5 py-0.5 rounded-full whitespace-nowrap" style="background: #009866;">{{ count($orderItems) }} part{{ count($orderItems) !== 1 ? 's' : '' }}</span>
        </div>

        <div class="space-y-3">
          @foreach($orderItemIds as $index => $itemId)
          <div wire:key="order-item-{{ $index }}" class="border border-gray-200 rounded-xl overflow-hidden bg-[#fafcfb] focus-within:border-[rgba(0,152,102,0.4)] transition-colors">
            <div class="flex items-center justify-between py-2.5 px-4 bg-white border-b border-gray-200 cursor-pointer" wire:click="toggleItem({{ $index }})">
              <div class="flex items-center gap-2.5 min-w-0">
                <span class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background: #009866;">{{ $index + 1 }}</span>
                <span class="text-sm font-medium text-gray-900 truncate">{{ !empty($partNames[$index] ?? null) ? $partNames[$index] : 'Part name…' }}</span>
              </div>
              <div class="flex items-center gap-1 flex-shrink-0">
                @if(count($orderItemIds) > 1)
                <button type="button" wire:click.stop="removeOrderItem({{ $index }})" class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-red-600" title="Remove part">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
                <span class="p-1.5 inline-block transition-transform duration-200 {{ in_array($index, $expandedItems) ? '' : '-rotate-90' }}">
                  <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </span>
              </div>
            </div>
            <div class="p-4 {{ in_array($index, $expandedItems) ? '' : 'hidden' }}">
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                <div>
                  <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Part Name <span style="color: #009866;">*</span></label>
                  <input type="text" wire:model.lazy="partNames.{{ $index }}" required placeholder="e.g. Brake Pads" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem]" />
                  @error('partNames.'.$index) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Quantity <span style="color: #009866;">*</span></label>
                  <input type="number" wire:model.lazy="quantities.{{ $index }}" required min="1" placeholder="1" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem]" />
                  @error('quantities.'.$index) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Part Number</label>
                  <input type="text" wire:model.lazy="partNumbers.{{ $index }}" placeholder="Optional" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem]" />
                </div>
              </div>
              <div class="mb-3">
                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Condition <span style="color: #009866;">*</span></label>
                <div class="flex gap-2 flex-wrap">
                  @foreach(['any' => 'Any', 'new' => 'New', 'used' => 'Used'] as $val => $label)
                  <label class="inline-flex items-center cursor-pointer">
                    <input type="radio" name="condition-{{ $index }}" wire:model.lazy="conditions.{{ $index }}" value="{{ $val }}" class="sr-only peer" />
                    <span class="px-4 py-2 rounded-full border-2 border-gray-200 text-sm font-medium text-gray-500 peer-checked:bg-[#009866] peer-checked:border-[#009866] peer-checked:text-white hover:border-[#009866] hover:text-[#009866] peer-checked:hover:bg-[#009866] peer-checked:hover:text-white transition-all">{{ $label }}</span>
                  </label>
                  @endforeach
                </div>
              </div>
              <div class="mb-3">
                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Details <span style="color: #009866;">*</span></label>
                <textarea wire:model.lazy="notes.{{ $index }}" required rows="3" placeholder="Side (left/right), engine size, OEM or aftermarket, any markings, urgency, budget range…" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem] resize-none leading-relaxed"></textarea>
                @error('notes.'.$index) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Photos (Optional)</label>
                @php $partPhotos = $orderItemImages[$index] ?? []; @endphp
                @if(count($partPhotos) > 0)
                <div class="flex flex-wrap gap-2 mb-2">
                  @foreach($partPhotos as $imgIdx => $photo)
                    @if($photo)
                    <div wire:key="part-{{ $index }}-photo-{{ $imgIdx }}" class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 shrink-0">
                      <img src="{{ $photo->temporaryUrl() }}" alt="" class="w-full h-full object-cover" />
                      <button type="button" wire:click="removeOrderItemImage({{ $index }}, {{ $imgIdx }})" class="absolute top-0.5 right-0.5 rounded-full bg-black/60 text-white p-0.5 hover:bg-red-600" title="Remove photo">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                      </button>
                    </div>
                    @endif
                  @endforeach
                </div>
                @endif
                @if(count($partPhotos) < 5)
                <label class="relative flex items-center gap-2.5 p-3 rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 cursor-pointer hover:border-[#009866] hover:bg-[rgba(0,152,102,0.08)] transition-all overflow-hidden">
                  <input type="file" wire:model="newPartImages.{{ $index }}" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                  <div wire:loading wire:target="newPartImages.{{ $index }}" class="absolute inset-0 bg-white/80 flex items-center justify-center z-20 text-xs font-medium text-gray-600">Uploading…</div>
                  <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
                    <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">Add a photo</p>
                    <span class="text-xs text-gray-500">One at a time — up to 5 · max 5 MB each</span>
                  </div>
                </label>
                @else
                <p class="text-xs text-gray-500">Maximum 5 photos for this part.</p>
                @endif
                @error('newPartImages.'.$index) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @error('orderItemImages.'.$index) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>
          @endforeach
        </div>

        <button type="button" wire:click="addOrderItem" class="w-full flex items-center justify-center gap-2 py-3 px-4 mt-3 rounded-xl border-2 border-dashed border-[#009866] font-semibold text-sm transition-colors" style="background: rgba(0,152,102,0.08); color: #009866;">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
          Add Another Part
        </button>
      </div>

      {{-- ④ DELIVERY --}}
      <div class="p-6 sm:p-8 border-b border-gray-200">
        <div class="flex items-center gap-2.5 mb-5">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: rgba(0,152,102,0.08);">
            <svg class="w-4 h-4" fill="none" stroke="#009866" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
          </div>
          <span class="text-sm font-semibold text-gray-900">Delivery</span>
        </div>
        <div class="mb-3">
          <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Street / Area <span style="color: #009866;">*</span></label>
          <textarea wire:model.defer="deliveryAddress" required rows="2" placeholder="Street, building, or landmark" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none text-[0.95rem] resize-none leading-relaxed"></textarea>
          @error('deliveryAddress') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">City <span style="color: #009866;">*</span></label>
            <select wire:model.defer="deliveryCity" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 focus:border-[#009866] focus:ring-[3px] focus:ring-[rgba(0,152,102,0.18)] focus:bg-white outline-none appearance-none cursor-pointer" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E&quot;); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px;">
              <option value="">Select region</option>
              @foreach($tanzaniaRegions as $region)
                <option value="{{ $region }}">{{ $region }}</option>
              @endforeach
            </select>
            @error('deliveryCity') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
        <div class="mt-4 rounded-r-lg py-2.5 px-4 text-sm leading-relaxed" style="background: rgba(0,152,102,0.08); border-left: 3px solid #009866; color: #007a52;">
          💡 <strong>Tip:</strong> A clear description + photo helps us source your parts faster with fewer follow-up questions.
        </div>
      </div>

      {{-- SUBMIT --}}
      <div class="p-6 sm:p-8">
        <div class="flex items-center justify-between mb-2.5">
          <span class="text-sm text-gray-500">Submitting <strong style="color: #007a52;">{{ count($orderItems) }} part{{ count($orderItems) !== 1 ? 's' : '' }}</strong> for your vehicle</span>
        </div>
        <button type="submit" wire:loading.attr="disabled" class="w-full py-4 rounded-xl text-white font-bold text-base flex items-center justify-center gap-2 transition-all disabled:opacity-50 hover:shadow-lg hover:-translate-y-px" style="background: #009866; box-shadow: 0 4px 20px rgba(0,152,102,0.35);">
          <span wire:loading.remove class="flex ">
           
            <div class=" mx-4 ">  {{ count($orderItems) > 1 ? 'Submit ' . count($orderItems) . ' Parts' : 'Submit Request' }}  </div>
           
          </span>
          <span wire:loading>Processing…</span>
        </button>
      </div>
    </form>
  </div>
  @endif
</div>

@push('scripts')
<script>
document.addEventListener('livewire:init', () => {
  Livewire.on('open-auth-modal', () => {
    const m = document.getElementById('authModal');
    const p = document.getElementById('authPanel');
    if (m && p) { m.classList.remove('hidden'); setTimeout(() => p.classList.remove('translate-x-full'), 10); document.body.style.overflow = 'hidden'; }
  });
});
</script>
@endpush
