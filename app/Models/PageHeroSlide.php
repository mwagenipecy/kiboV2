<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PageHeroSlide extends Model
{
    protected $fillable = [
        'page_hero_id',
        'image_path',
        'headline',
        'subheadline',
        'cta_label',
        'cta_url',
        'overlay_style',
        'text_align',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function pageHero(): BelongsTo
    {
        return $this->belongsTo(PageHero::class, 'page_hero_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function toCarouselArray(): array
    {
        return [
            'image_url' => $this->image_url,
            'headline' => $this->headline,
            'subheadline' => $this->subheadline,
            'cta_label' => $this->cta_label,
            'cta_url' => $this->cta_url,
            'overlay_style' => $this->overlay_style,
            'text_align' => $this->text_align,
        ];
    }
}
