<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Promotion</h1>
            <p class="mt-1 text-sm text-gray-500">
                @if($screen === 'list')
                    Your sent promotion messages. Click View to see who received each one.
                @elseif($screen === 'view')
                    Recipients for this message.
                @else
                    Create a new promotion and send it to partners or customers. You can add images by pasting image URLs.
                @endif
            </p>
        </div>
        @if($screen === 'list')
            <button type="button" wire:click="showCreate"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg font-semibold text-white bg-[#009866] hover:bg-[#007a52] focus:ring-2 focus:ring-offset-2 focus:ring-[#009866]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New promotion
            </button>
        @elseif($screen === 'view')
            <button type="button" wire:click="showList"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to list
            </button>
        @else
            <button type="button" wire:click="showList"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Cancel
            </button>
        @endif
    </div>

    @if (session('promotion_success'))
        <div class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('promotion_success') }}
        </div>
    @endif

    @if($screen === 'list')
        {{-- List of promotion messages --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Promotion messages</h2>
            </div>
            <div class="overflow-x-auto">
                @forelse($campaigns as $campaign)
                    <div class="border-b border-gray-200 last:border-b-0 hover:bg-gray-50/50">
                        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate">{{ $campaign->subject }}</p>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    {{ $campaign->created_at->format('M j, Y \a\t g:i A') }}
                                    · {{ $campaign->sentBy?->name ?? '—' }}
                                    · {{ $campaign->email_logs_count }} recipient(s)
                                </p>
                            </div>
                            <button type="button" wire:click="viewCampaign({{ $campaign->id }})"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-[#009866] hover:bg-[#009866]/10 rounded-lg whitespace-nowrap">
                                View recipients
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <p>No promotion messages yet.</p>
                        <button type="button" wire:click="showCreate" class="mt-2 text-[#009866] font-medium hover:underline">Create your first promotion</button>
                    </div>
                @endforelse
            </div>
        </div>
    @elseif($screen === 'view' && $viewCampaign)
        {{-- View single campaign: message preview + recipients table --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Message</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $viewCampaign->subject }}</p>
                </div>
                <div class="p-6 prose prose-sm max-w-none text-gray-700">
                    {!! $viewCampaign->body_html !!}
                </div>
                <div class="px-6 py-3 border-t border-gray-200 text-xs text-gray-500">
                    Sent {{ $viewCampaign->created_at->format('M j, Y \a\t g:i A') }} by {{ $viewCampaign->sentBy?->name ?? '—' }}
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Recipients</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent at</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($viewCampaign->emailLogs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="font-medium text-gray-900">{{ $log->recipient_name }}</span>
                                        <span class="text-gray-500 block">{{ $log->recipient_email }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ str_replace('_', ' ', $log->recipient_type ?? '—') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->status === 'sent')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Sent</span>
                                        @elseif($log->status === 'failed')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800" title="{{ $log->error_message }}">Failed</span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->sent_at?->format('M j, H:i') ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No recipients recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($screen === 'create')
        {{-- Create form with message + images --}}
        <form wire:submit="sendPromotion" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 space-y-8">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#009866] text-white text-sm font-semibold">1</span>
                        <h2 class="text-lg font-semibold text-gray-900">Who should receive this email?</h2>
                    </div>
                    <p class="text-sm text-gray-600">Tick one or more groups. Each person receives only one email.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        @foreach ($groupLabels as $value => $label)
                            <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 hover:border-[#009866]/40 hover:bg-gray-50/50 cursor-pointer transition-colors has-[:checked]:border-[#009866] has-[:checked]:bg-[#009866]/5">
                                <input type="checkbox" wire:model.live="recipientGroups" value="{{ $value }}"
                                       class="mt-1 rounded border-gray-300 text-[#009866] focus:ring-[#009866]">
                                <div class="min-w-0">
                                    <span class="text-sm font-medium text-gray-900 block">{{ $label }}</span>
                                    <span class="text-xs text-gray-500 block mt-0.5">{{ $groupDescriptions[$value] ?? '' }}</span>
                                    @if(isset($recipientCounts[$value]) && $recipientCounts[$value] > 0)
                                        <span class="text-xs text-[#009866] font-medium mt-1 block">{{ $recipientCounts[$value] }} recipient(s)</span>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('recipientGroups')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($totalRecipients > 0)
                        <p class="text-sm text-gray-700 font-medium">Total: <span class="text-[#009866]">{{ $totalRecipients }}</span> people will receive this email.</p>
                    @endif
                </div>

                <hr class="border-gray-200" />

                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#009866] text-white text-sm font-semibold">2</span>
                        <h2 class="text-lg font-semibold text-gray-900">Email subject</h2>
                    </div>
                    <input type="text" wire:model="subject" maxlength="255"
                           class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-[#009866] focus:ring-[#009866] text-gray-900 placeholder-gray-400"
                           placeholder="e.g. New offers and updates from Kibo">
                    @error('subject')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-200" />

                <div class="space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-[#009866] text-white text-sm font-semibold">3</span>
                            <h2 class="text-lg font-semibold text-gray-900">Your message</h2>
                        </div>
                        <button type="button" wire:click="useSampleMessage"
                                class="text-sm text-[#009866] hover:underline font-medium">
                            Use sample message
                        </button>
                    </div>
                    <textarea wire:model="message" rows="6" maxlength="10000"
                              class="w-full rounded-lg border border-gray-300 shadow-sm focus:border-[#009866] focus:ring-[#009866] text-gray-900 placeholder-gray-400"
                              placeholder="Type your message here..."></textarea>
                    @error('message')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-3">
                    <h2 class="text-lg font-semibold text-gray-900">Add images (optional)</h2>
                    <p class="text-sm text-gray-600">Paste the full URL of an image (e.g. from your website or a hosted image). It will appear in the email below your message.</p>
                    @foreach($imageUrls as $index => $url)
                        <div class="flex gap-2 items-start">
                            <input type="url" wire:model="imageUrls.{{ $index }}"
                                   class="flex-1 rounded-lg border border-gray-300 shadow-sm focus:border-[#009866] focus:ring-[#009866] text-gray-900 placeholder-gray-400"
                                   placeholder="https://example.com/image.jpg">
                            @if(count($imageUrls) > 1)
                                <button type="button" wire:click="removeImageUrl({{ $index }})"
                                        class="p-2 text-gray-400 hover:text-red-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                    <button type="button" wire:click="addImageUrl"
                            class="text-sm text-[#009866] hover:underline font-medium">
                        + Add another image URL
                    </button>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                    <p class="text-sm text-gray-500">Emails are sent in the background. You can view recipients for this message from the list after sending.</p>
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg font-semibold text-white bg-[#009866] hover:bg-[#007a52] focus:ring-2 focus:ring-offset-2 focus:ring-[#009866] disabled:opacity-50">
                        @if($totalRecipients > 0)
                            <span wire:loading.remove>Send to {{ $totalRecipients }} {{ $totalRecipients === 1 ? 'person' : 'people' }}</span>
                        @else
                            <span wire:loading.remove>Send promotion emails</span>
                        @endif
                        <span wire:loading>Sending…</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
