<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLinkItem extends Model
{
    public const STATUS_UNPAID = 'unpaid';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_PAID = 'paid';

    protected $fillable = [
        'payment_link_id',
        'item_code',
        'type',
        'product_service_reference',
        'product_service_name',
        'description',
        'amount',
        'minimum_amount',
        'is_required',
        'allow_partial',
        'payment_status',
        'paid_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'is_required' => 'boolean',
        'allow_partial' => 'boolean',
    ];

    public function paymentLink(): BelongsTo
    {
        return $this->belongsTo(PaymentLink::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::STATUS_UNPAID);
    }

    public function scopePartial($query)
    {
        return $query->where('payment_status', self::STATUS_PARTIAL);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    public function isPartial(): bool
    {
        return $this->payment_status === self::STATUS_PARTIAL;
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === self::STATUS_UNPAID;
    }

    /**
     * Update payment status from webhook (call when webhook is implemented).
     */
    public function recordPayment(float $amount): void
    {
        $this->paid_amount = ($this->paid_amount ?? 0) + $amount;
        $fullAmount = (float) $this->amount;
        if ($this->paid_amount >= $fullAmount) {
            $this->payment_status = self::STATUS_PAID;
            $this->paid_amount = $fullAmount;
        } else {
            $this->payment_status = self::STATUS_PARTIAL;
        }
        $this->save();
    }
}
