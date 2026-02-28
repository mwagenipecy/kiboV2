<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealerSubscription extends Model
{
    protected $fillable = [
        'entity_id',
        'pricing_plan_id',
        'status',
        'amount',
        'currency',
        'paid_at',
        'expires_at',
        'payment_reference',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending_payment';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Scope for pending payment (awaiting dealer to pay)
     */
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
