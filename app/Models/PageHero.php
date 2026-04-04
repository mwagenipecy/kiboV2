<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageHero extends Model
{
    protected $fillable = [
        'slug',
        'label',
    ];

    public function slides(): HasMany
    {
        return $this->hasMany(PageHeroSlide::class);
    }

    public function activeSlidesOrdered(): HasMany
    {
        return $this->slides()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public static function fallbackSlidesForSlug(string $slug): array
    {
        return match ($slug) {
            'cars' => [[
                'image_url' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=2000&q=80',
                'headline' => null,
                'subheadline' => null,
                'cta_label' => null,
                'cta_url' => null,
                'overlay_style' => 'dark_bottom',
                'text_align' => 'center',
            ]],
            'trucks' => [[
                'image_url' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?auto=format&fit=crop&w=2000&q=80',
                'headline' => null,
                'subheadline' => null,
                'cta_label' => null,
                'cta_url' => null,
                'overlay_style' => 'dark_bottom',
                'text_align' => 'center',
            ]],
            'vans' => [[
                'image_url' => 'https://images.unsplash.com/photo-1527786356703-4b100091cd2c?auto=format&fit=crop&w=2000&q=80',
                'headline' => null,
                'subheadline' => null,
                'cta_label' => null,
                'cta_url' => null,
                'overlay_style' => 'dark_bottom',
                'text_align' => 'center',
            ]],
            'spare_parts' => [[
                'image_url' => asset('hero/spare/spareHero.png'),
                'headline' => 'Order Spare Parts',
                'subheadline' => 'Fill in the details below — we\'ll find the best match for you.',
                'cta_label' => null,
                'cta_url' => null,
                'overlay_style' => 'gradient_emerald',
                'text_align' => 'center',
            ]],
            'home' => [[
                'image_url' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=2000&q=80',
                'headline' => null,
                'subheadline' => null,
                'cta_label' => null,
                'cta_url' => null,
                'overlay_style' => 'dark_bottom',
                'text_align' => 'center',
            ]],
            default => [[
                'image_url' => 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?auto=format&fit=crop&w=2000&q=80',
                'headline' => null,
                'subheadline' => null,
                'cta_label' => null,
                'cta_url' => null,
                'overlay_style' => 'dark_bottom',
                'text_align' => 'center',
            ]],
        };
    }
}
