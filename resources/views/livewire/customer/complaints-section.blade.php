<div class="bg-white">
    <div class="text-center mb-10">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Complaints &amp; Feedback</h1>
        <p class="mt-2 text-gray-600">Submit a complaint or track an existing one using your <strong>tracking number</strong>.</p>
    </div>

    {{-- Tabs --}}
    <div class="flex rounded-xl overflow-hidden border border-gray-200 bg-gray-50/80 mb-0">
        <button type="button"
                wire:click="switchToSubmit"
                class="flex-1 px-5 py-3.5 text-sm font-medium transition-colors {{ $activeTab === 'submit' ? 'bg-white text-[#009866] shadow-sm border border-gray-200 border-b-0 -mb-px rounded-t-xl' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
            Submit a complaint
        </button>
        <button type="button"
                wire:click="switchToTrack"
                class="flex-1 px-5 py-3.5 text-sm font-medium transition-colors {{ $activeTab === 'track' ? 'bg-white text-[#009866] shadow-sm border border-gray-200 border-b-0 -mb-px rounded-t-xl' : 'text-gray-600 hover:text-gray-900 hover:bg-white/50' }}">
            Track complaint
        </button>
    </div>

    <div class="relative bg-white border border-t-0 border-gray-200 rounded-b-xl shadow-sm p-6 sm:p-8 min-h-[320px]">
        {{-- Loading overlay --}}
        <div wire:loading wire:target="submitComplaint,trackComplaints" class="absolute inset-0 z-10 flex items-center justify-center bg-white/95 rounded-b-xl">
            <div class="flex flex-col items-center gap-4">
                <svg class="animate-spin h-12 w-12 text-[#009866]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-sm font-medium text-gray-700">Loading… Please wait.</p>
            </div>
        </div>

        @if($submittedNumber)
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#009866]/10 text-[#009866] mb-5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Complaint submitted</h3>
                <p class="mt-2 text-gray-600">Your complaint has been received and we are working on it. A confirmation email has been sent to you.</p>
                <p class="mt-5 text-2xl font-mono font-bold text-[#009866] tracking-tight">{{ $submittedNumber }}</p>
                <p class="mt-2 text-sm text-gray-500">Save this <strong>tracking number</strong>. Use the &quot;Track complaint&quot; tab and enter this number to check status anytime.</p>
                <button type="button" wire:click="switchToSubmit" class="mt-6 inline-flex items-center px-4 py-2 text-sm font-medium text-[#009866] hover:bg-[#009866]/5 rounded-lg transition-colors">
                    Submit another complaint
                </button>
            </div>
        @elseif($activeTab === 'submit')
            <form wire:submit.prevent="submitComplaint" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="complaint-name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="complaint-name" wire:model="name" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="complaint-email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="complaint-email" wire:model="email" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="complaint-phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone (optional)</label>
                    <input type="text" id="complaint-phone" wire:model="phone" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors" placeholder="For tracking">
                    @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="complaint-category" class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
                    <select id="complaint-category" wire:model="category" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors">
                        @foreach(\App\Models\Complaint::CATEGORIES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="complaint-subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                    <input type="text" id="complaint-subject" wire:model="subject" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors">
                    @error('subject') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="complaint-message" class="block text-sm font-medium text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                    <textarea id="complaint-message" wire:model="message" rows="4" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors resize-y"></textarea>
                    @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 rounded-xl bg-[#009866] text-white font-semibold hover:bg-[#007a52] disabled:opacity-60 transition-colors shadow-sm">
                        <span wire:loading.remove wire:target="submitComplaint">Submit complaint</span>
                        <span wire:loading wire:target="submitComplaint">Submitting…</span>
                    </button>
                </div>
            </form>
        @else
            {{-- Track by tracking number only --}}
            <form wire:submit.prevent="trackComplaints" class="space-y-5">
                <p class="text-sm text-gray-600">Enter the <strong>tracking number</strong> you received when you submitted your complaint (e.g. KIBO-C-2026-00001).</p>
                <div>
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1.5">Tracking number</label>
                    <input type="text" id="tracking_number" wire:model="tracking_number" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-gray-900 placeholder-gray-400 focus:border-[#009866] focus:ring-2 focus:ring-[#009866]/20 transition-colors font-mono" placeholder="e.g. KIBO-C-A7X9K2M4P1Qw" autocomplete="off">
                    @error('tracking_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" wire:loading.attr="disabled" class="px-6 py-3 rounded-xl bg-[#009866] text-white font-semibold hover:bg-[#007a52] disabled:opacity-60 transition-colors shadow-sm">
                        <span wire:loading.remove wire:target="trackComplaints">Track</span>
                        <span wire:loading wire:target="trackComplaints">Searching…</span>
                    </button>
                </div>
            </form>

            @if($trackError)
                <div class="mt-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm">
                    {{ $trackError }}
                </div>
            @endif

            @if($trackResults !== null && $trackResults->isNotEmpty())
                <div class="mt-8 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide">Complaint status</h3>
                    @foreach($trackResults as $c)
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50 hover:bg-gray-50 transition-colors">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <span class="font-mono font-semibold text-[#009866]">{{ $c->complaint_number }}</span>
                                <span class="text-xs px-2.5 py-1 rounded-lg font-medium
                                    @if($c->status === 'closed') bg-gray-200 text-gray-700
                                    @elseif($c->status === 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm font-medium text-gray-900">{{ $c->subject }}</p>
                            <p class="text-xs text-gray-500 mt-1">Submitted {{ $c->created_at->format('M j, Y') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
