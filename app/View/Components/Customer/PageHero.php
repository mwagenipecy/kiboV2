<?php

namespace App\View\Components\Customer;

use App\Models\PageHero as PageHeroConfig;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageHero extends Component
{
    /** @var list<array<string, mixed>> */
    public array $slides;

    public function __construct(
        public string $slug,
        public string $variant = 'floating_overlay',
        public ?string $heroHeadline = null,
        public ?string $heroSubheadline = null,
    ) {
        $this->slides = $this->resolveSlides();

        if (($this->heroHeadline !== null || $this->heroSubheadline !== null) && isset($this->slides[0])) {
            if ($this->heroHeadline !== null) {
                $this->slides[0]['headline'] = $this->heroHeadline;
            }
            if ($this->heroSubheadline !== null) {
                $this->slides[0]['subheadline'] = $this->heroSubheadline;
            }
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function resolveSlides(): array
    {
        $hero = PageHeroConfig::query()
            ->where('slug', $this->slug)
            ->with(['slides' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
            }])
            ->first();

        if ($hero instanceof PageHeroConfig && $hero->slides->isNotEmpty()) {
            return $hero->slides->map(fn ($slide) => $slide->toCarouselArray())->values()->all();
        }

        return PageHeroConfig::fallbackSlidesForSlug($this->slug);
    }

    public function render(): View
    {
        return view('components.customer.page-hero');
    }
}
