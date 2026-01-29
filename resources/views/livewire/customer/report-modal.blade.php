<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click="close">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6" wire:click.stop>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Report This Listing</h2>
                <button wire:click="close" class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @if(session('report_success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm text-green-700 font-medium">{{ session('report_success') }}</span>
                </div>
            </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Report *</label>
                    <select wire:model="reason" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Select a reason</option>
                        <option value="fraudulent">Fraudulent or Scam</option>
                        <option value="misleading">Misleading Information</option>
                        <option value="inappropriate">Inappropriate Content</option>
                        <option value="duplicate">Duplicate Listing</option>
                        <option value="wrong_category">Wrong Category</option>
                        <option value="spam">Spam</option>
                        <option value="other">Other</option>
                    </select>
                    @error('reason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Details</label>
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Please provide more information about why you're reporting this listing..."></textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                @guest
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                        <input type="text" wire:model="reporterName" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('reporterName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                        <input type="email" wire:model="reporterEmail" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @error('reporterEmail') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                @endguest

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="close" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
        <div class="fixed inset-0 bg-black/50 -z-10" wire:click="close"></div>
    </div>
    @endif
</div>
