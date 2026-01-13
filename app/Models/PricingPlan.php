<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $table = 'advertising_pricing';

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'currency',
        'duration_days',
        'features',
        'is_featured',
        'is_popular',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'duration_days' => 'integer',
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
}
