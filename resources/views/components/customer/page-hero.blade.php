@php
    $sectionMb = $variant === 'floating_overlay' ? 'mb-12 sm:mb-16 md:mb-20' : 'mb-0';
    $heroH = $variant === 'centered' ? 'h-44 sm:h-52 md:h-56' : 'h-56 sm:h-64 md:h-80';
    $justifyContent = $variant === 'centered' ? 'justify-center' : 'justify-end sm:justify-center';
@endphp

<section class="relative bg-white {{ $sectionMb }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 {{ $variant === 'floating_overlay' ? 'py-6 sm:py-8' : 'py-5 sm:py-6' }}">
        <div class="relative">
            <div
                class="relative {{ $heroH }} rounded-2xl overflow-hidden bg-gray-900"
                x-data="{
                    i: 0,
                    slides: @js($slides),
                    t: null,
                    start() {
                        if (this.slides.length < 2) return;
                        this.t = setInterval(() => { this.i = (this.i + 1) % this.slides.length }, 6500);
                    },
                    stop() { if (this.t) clearInterval(this.t); },
                    go(n) { this.i = (n + this.slides.length) % this.slides.length; }
                }"
                x-init="start()"
                @mouseenter="stop()"
                @mouseleave="start()"
            >
                <template x-for="(slide, idx) in slides" :key="'slide-' + idx">
                    <div
                        class="absolute inset-0 transition-opacity duration-700 ease-out"
                        :class="i === idx ? 'opacity-100 z-[1]' : 'opacity-0 z-0 pointer-events-none'"
                    >
                        <img
                            :src="slide.image_url"
                            :alt="slide.headline || 'Hero'"
                            class="absolute inset-0 w-full h-full object-cover"
                            loading="eager"
                            decoding="async"
                        />
                        <div
                            x-show="slide.overlay_style === 'gradient_emerald'"
                            x-cloak
                            class="absolute inset-0 bg-gradient-to-br from-emerald-900/50 to-emerald-950/70 pointer-events-none"
                        ></div>
                        <div
                            class="absolute inset-0 pointer-events-none"
                            :class="{
                                'bg-gradient-to-t from-black/65 via-black/25 to-transparent': slide.overlay_style === 'dark_bottom',
                                'bg-black/45': slide.overlay_style === 'dark_full',
                                'bg-gradient-to-t from-black/55 via-black/20 to-black/10': slide.overlay_style === 'gradient_emerald',
                                '': slide.overlay_style === 'none' || !slide.overlay_style
                            }"
                        ></div>
                        <div
                            class="relative z-10 h-full flex flex-col {{ $justifyContent }} p-6 sm:p-8 md:p-10 pointer-events-none"
                            :class="{
                                'items-center text-center': slide.text_align !== 'left',
                                'items-start text-left': slide.text_align === 'left'
                            }"
                            x-show="slide.headline || slide.subheadline || (slide.cta_label && slide.cta_url)"
                        >
                            <h1
                                x-show="slide.headline"
                                class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 drop-shadow-lg max-w-3xl text-white"
                                x-text="slide.headline"
                            ></h1>
                            <p
                                x-show="slide.subheadline"
                                class="text-sm sm:text-base md:text-lg max-w-2xl drop-shadow-md text-white/95"
                                x-text="slide.subheadline"
                            ></p>
                            <a
                                x-show="slide.cta_label && slide.cta_url"
                                :href="slide.cta_url"
                                class="mt-4 inline-flex items-center px-5 py-2.5 rounded-xl bg-[#009866] text-white text-sm font-semibold hover:bg-[#007a52] transition-colors shadow-lg pointer-events-auto"
                                x-text="slide.cta_label"
                            ></a>
                        </div>
                    </div>
                </template>

                <template x-if="slides.length > 1">
                    <div class="absolute bottom-3 left-0 right-0 z-20 flex justify-center gap-2 pointer-events-auto">
                        <template x-for="(s, idx) in slides" :key="'dot-' + idx">
                            <button
                                type="button"
                                class="h-2 rounded-full transition-all shadow-sm"
                                :class="i === idx ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                                @click="go(idx)"
                                :aria-label="'Slide ' + (idx + 1)"
                            ></button>
                        </template>
                    </div>
                </template>

                <template x-if="slides.length > 1">
                    <div>
                        <button
                            type="button"
                            class="absolute left-2 top-1/2 -translate-y-1/2 z-20 p-2 rounded-full bg-black/35 text-white hover:bg-black/50 pointer-events-auto hidden sm:flex"
                            @click="go(i - 1)"
                            aria-label="Previous slide"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button
                            type="button"
                            class="absolute right-2 top-1/2 -translate-y-1/2 z-20 p-2 rounded-full bg-black/35 text-white hover:bg-black/50 pointer-events-auto hidden sm:flex"
                            @click="go(i + 1)"
                            aria-label="Next slide"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            @if($variant === 'floating_overlay' && isset($overlay))
                <div class="relative mt-6 sm:mt-0 sm:absolute sm:bottom-0 sm:left-0 sm:right-0 sm:transform sm:translate-y-1/2 px-4 z-30">
                    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-lg shadow-black/10 border border-gray-100 p-3 sm:p-4">
                        {{ $overlay }}
                    </div>
                </div>
            @endif
        </div>
        @if($variant === 'floating_overlay')
            <div class="block sm:hidden h-4" aria-hidden="true"></div>
        @endif
    </div>
</section>
