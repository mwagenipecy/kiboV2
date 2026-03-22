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
    ) {
        $this->slides = $this->resolveSlides();
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
