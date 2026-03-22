@php
    $input = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-[#009866] focus:outline-none focus:ring-2 focus:ring-[#009866]/25';
    $select = 'w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-[#009866] focus:outline-none focus:ring-2 focus:ring-[#009866]/25';
    $fileWrap = 'rounded-lg border border-gray-300 border-dashed bg-gray-50 px-3 py-3 shadow-sm';
    $editingSlide = $editingSlideId ? $managedSlides->firstWhere('id', $editingSlideId) : null;
@endphp

{{-- Single root: Livewire reliably morphs one root; modal toggles inside this wrapper. z-index above admin sidebar (9999). --}}
<div class="relative min-w-0" wire:key="page-hero-manager-root">
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Hero banners</h1>
                <p class="mt-1 text-sm text-gray-600 max-w-2xl">Slideshow content for public pages. Use wide images (~2000px) for best results.</p>
            </div>
            <button
                type="button"
                wire:click="openAddModal"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-[#009866] text-white text-sm font-semibold shadow-sm hover:bg-[#007a52] shrink-0"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add slide
            </button>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-emerald-50 text-emerald-800 px-4 py-3 text-sm border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-red-50 text-red-800 px-4 py-3 text-sm border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-5 mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Page</label>
            <select wire:model.live="selectedHeroId" class="{{ $select }} max-w-xl">
                @foreach($heroes as $h)
                    <option value="{{ $h->id }}">{{ $h->label }} — {{ $h->slug }}</option>
                @endforeach
            </select>
            @if($currentHero)
                <p class="mt-2 text-xs text-gray-500">Slug: <code class="bg-gray-100 border border-gray-200 px-1.5 py-0.5 rounded text-gray-800">{{ $currentHero->slug }}</code></p>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-900">Slides for this page</h2>
                <span class="text-xs text-gray-500">{{ $managedSlides->count() }} total</span>
            </div>

            <div class="overflow-x-auto">
                @if($managedSlides->isEmpty())
                    <div class="p-10 text-center">
                        <p class="text-sm text-gray-600 mb-4">No custom slides — the site uses the default hero until you add one.</p>
                        <button type="button" wire:click="openAddModal" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add first slide
                        </button>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide w-28">Preview</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide min-w-[140px]">Headline</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide min-w-[180px] hidden md:table-cell">Subheadline</th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide hidden lg:table-cell">Overlay</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wide w-24">Active</th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide w-52">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($managedSlides as $slide)
                                <tr class="{{ $slide->is_active ? 'hover:bg-gray-50/80' : 'bg-gray-50/60 opacity-70 hover:bg-gray-50' }}">
                                    <td class="px-4 py-3 align-middle">
                                        <div class="w-20 h-12 rounded-md overflow-hidden border border-gray-200 bg-gray-100">
                                            <img src="{{ $slide->image_url }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-middle">
                                        <span class="font-medium text-gray-900">{{ $slide->headline ?: '—' }}</span>
                                        @if($slide->cta_label)
                                            <p class="text-xs text-[#009866] mt-0.5">Btn: {{ Str::limit($slide->cta_label, 24) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-middle text-gray-600 hidden md:table-cell max-w-xs">
                                        <span class="line-clamp-2">{{ $slide->subheadline ?: '—' }}</span>
                                    </td>
                                    <td class="px-4 py-3 align-middle text-gray-600 hidden lg:table-cell whitespace-nowrap">
                                        {{ str_replace('_', ' ', $slide->overlay_style) }}
                                        <span class="text-gray-400">·</span>
                                        {{ $slide->text_align }}
                                    </td>
                                    <td class="px-4 py-3 align-middle text-center">
                                        <button
                                            type="button"
                                            wire:click="toggleActive({{ $slide->id }})"
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $slide->is_active ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-gray-200 bg-white text-gray-500' }}"
                                        >
                                            {{ $slide->is_active ? 'On' : 'Off' }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-3 align-middle text-right">
                                        <div class="flex flex-wrap items-center justify-end gap-1">
                                            <button type="button" wire:click="startEdit({{ $slide->id }})" class="px-2 py-1 text-xs font-medium text-[#009866] hover:bg-emerald-50 rounded-md border border-transparent hover:border-emerald-100">Edit</button>
                                            <button type="button" wire:click="moveUp({{ $slide->id }})" class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 rounded-md border border-gray-200" title="Move up">↑</button>
                                            <button type="button" wire:click="moveDown({{ $slide->id }})" class="px-2 py-1 text-xs text-gray-600 hover:bg-gray-100 rounded-md border border-gray-200" title="Move down">↓</button>
                                            <button type="button" wire:click="deleteSlide({{ $slide->id }})" wire:confirm="Remove this slide and delete its image file?" class="px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-md border border-transparent hover:border-red-100">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    @if($showModal)
        <div
            class="fixed inset-0 z-[10050] flex items-center justify-center overflow-y-auto p-4 sm:p-6"
            aria-modal="true"
            role="dialog"
            wire:key="hero-slide-modal-overlay"
        >
            {{-- Backdrop: sibling of panel so clicks on the form never hit the backdrop --}}
            <button
                type="button"
                wire:click="closeModal"
                class="fixed inset-0 w-full h-full border-0 bg-gray-900/60 backdrop-blur-[2px] cursor-default"
                aria-label="Close dialog"
            ></button>

            <div
                class="relative z-10 w-full max-w-lg max-h-[min(90vh,720px)] overflow-y-auto rounded-2xl bg-white shadow-xl border border-gray-200 my-auto"
                wire:key="hero-slide-modal-panel-{{ $editingSlideId ?? 'new' }}"
            >
                <div class="sticky top-0 z-10 flex items-start justify-between gap-4 border-b border-gray-200 bg-white px-5 py-4 rounded-t-2xl">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $editingSlideId ? 'Edit slide' : 'Add slide' }}</h3>
                        @if($currentHero)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $currentHero->label }}</p>
                        @endif
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800 border border-transparent hover:border-gray-200"
                        aria-label="Close"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="saveSlide" class="px-5 py-4 space-y-4">
                    @if($editingSlide && ! $image)
                        <div class="rounded-lg border border-gray-200 overflow-hidden bg-gray-50">
                            <p class="text-xs font-medium text-gray-600 px-3 py-2 border-b border-gray-200 bg-gray-100">Current image</p>
                            <div class="p-3">
                                <img src="{{ $editingSlide->image_url }}" alt="" class="w-full max-h-40 object-cover rounded-md border border-gray-200">
                            </div>
                            <p class="text-xs text-gray-500 px-3 pb-3">Upload a new file below to replace it.</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Image @if(!$editingSlideId)<span class="text-red-600">*</span>@else<span class="text-gray-400 font-normal">(optional)</span>@endif</label>
                        <div class="{{ $fileWrap }}">
                            <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border file:border-gray-300 file:bg-white file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-50">
                        </div>
                        @error('image') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                        <div wire:loading wire:target="image" class="text-xs text-gray-500 mt-1.5">Uploading…</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Headline</label>
                        <input type="text" wire:model="headline" class="{{ $input }}" placeholder="Optional">
                        @error('headline') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Subheadline</label>
                        <textarea wire:model="subheadline" rows="3" class="{{ $input }} min-h-[4.5rem]" placeholder="Optional"></textarea>
                        @error('subheadline') <p class="text-red-600 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Button label</label>
                            <input type="text" wire:model="cta_label" class="{{ $input }}" placeholder="e.g. View offers">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Button link</label>
                            <input type="text" wire:model="cta_url" class="{{ $input }}" placeholder="https://… or /path">
                        </div>
                    </div>
                    @error('cta_url') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Overlay</label>
                            <select wire:model="overlay_style" class="{{ $select }}">
                                <option value="dark_bottom">Dark gradient (bottom)</option>
                                <option value="dark_full">Dark veil (full)</option>
                                <option value="gradient_emerald">Emerald tint + gradient</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Text alignment</label>
                            <select wire:model="text_align" class="{{ $select }}">
                                <option value="center">Center</option>
                                <option value="left">Left</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-[#009866] text-white text-sm font-semibold hover:bg-[#007a52] shadow-sm">
                            {{ $editingSlideId ? 'Save changes' : 'Add slide' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
