<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentLink extends Model
{
    protected $fillable = [
        'link_id',
        'short_code',
        'payment_url',
        'qr_code_data',
        'target_type',
        'is_public',
        'total_amount',
        'currency',
        'description',
        'customer_reference',
        'customer_name',
        'customer_phone',
        'customer_email',
        'expires_at',
        'max_uses',
        'is_reusable',
        'allowed_networks',
        'api_request_id',
        'api_response_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_reusable' => 'boolean',
        'total_amount' => 'integer',
        'allowed_networks' => 'array',
        'expires_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PaymentLinkItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentLinkTransaction::class)->orderByDesc('paid_at');
    }

    public function scopeUnpaidItems($query)
    {
        return $query->whereHas('items', fn ($q) => $q->where('payment_status', 'unpaid'));
    }

    public function scopeWithPartialItems($query)
    {
        return $query->whereHas('items', fn ($q) => $q->where('payment_status', 'partial'));
    }

    public function scopeFullyPaid($query)
    {
        return $query->whereDoesntHave('items', fn ($q) =>
            $q->whereIn('payment_status', ['unpaid', 'partial'])
        );
    }

    public function getTotalPaidAmountAttribute(): float
    {
        return (float) $this->items->sum('paid_amount');
    }

    public function getOverallPaymentStatusAttribute(): string
    {
        $statuses = $this->items->pluck('payment_status')->unique();
        if ($statuses->contains('unpaid') || $statuses->contains('partial')) {
            return $statuses->contains('paid') ? 'partial' : ($statuses->contains('partial') ? 'partial' : 'unpaid');
        }
        return 'paid';
    }
}
