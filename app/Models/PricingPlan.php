<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $table = 'advertising_pricing';

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'price',
        'currency',
        'duration_days',
        'max_listings',
        'max_trucks',
        'max_leases',
        'features',
        'is_featured',
        'is_popular',
        'is_active',
        'is_free_tier',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'is_free_tier' => 'boolean',
        'duration_days' => 'integer',
        'max_listings' => 'integer',
        'max_trucks' => 'integer',
        'max_leases' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Scope to get active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get plans by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    /**
     * Paid dealer advertising (excludes free tier — used for public pricing & checkout).
     */
    public function scopeBillable($query)
    {
        return $query->where('is_free_tier', false);
    }

    public function isBillable(): bool
    {
        return ! $this->is_free_tier;
    }

    /**
     * Active “Free” cars tier row (limits for dealers without a paid subscription).
     */
    public static function activeCarsFreeTier(): ?self
    {
        return static::query()
            ->where('category', 'cars')
            ->where('is_free_tier', true)
            ->where('is_active', true)
            ->first();
    }
}
