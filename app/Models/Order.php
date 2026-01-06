<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'vehicle_id',
        'order_type',
        'status',
        'fee',
        'payment_required',
        'payment_completed',
        'payment_method',
        'payment_reference',
        'paid_at',
        'order_data',
        'customer_notes',
        'admin_notes',
        'processed_by',
        'processed_at',
        'rejection_reason',
        'completed_at',
        'completion_data',
    ];

    protected $casts = [
        'order_type' => OrderType::class,
        'status' => OrderStatus::class,
        'order_data' => 'array',
        'completion_data' => 'array',
        'payment_required' => 'boolean',
        'payment_completed' => 'boolean',
        'paid_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . strtoupper(uniqid());
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle associated with the order
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who processed the order
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING->value);
    }

    /**
     * Scope for orders by type
     */
    public function scopeOfType($query, OrderType $type)
    {
        return $query->where('order_type', $type->value);
    }

    /**
     * Scope for user orders
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for payment required orders
     */
    public function scopePaymentRequired($query)
    {
        return $query->where('payment_required', true);
    }

    /**
     * Scope for unpaid orders
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_required', true)
                     ->where('payment_completed', false);
    }

    /**
     * Check if order requires payment
     */
    public function requiresPayment(): bool
    {
        return $this->payment_required && !$this->payment_completed;
    }

    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status->value === OrderStatus::PENDING->value;
    }

    /**
     * Check if order is approved
     */
    public function isApproved(): bool
    {
        return $this->status->value === OrderStatus::APPROVED->value;
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status->value === OrderStatus::COMPLETED->value;
    }

    /**
     * Mark order as paid
     */
    public function markAsPaid(string $paymentMethod, string $paymentReference = null)
    {
        $this->update([
            'payment_completed' => true,
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'paid_at' => now(),
        ]);
        
    }

    /**
     * Approve the order
     */
    public function approve($processedBy = null)
    {
        $this->update([
            'status' => OrderStatus::APPROVED->value,
            'processed_by' => $processedBy,
            'processed_at' => now(),
        ]);
    }

    /**
     * Reject the order
     */
    public function reject(string $reason, $processedBy = null)
    {
        $this->update([
            'status' => OrderStatus::REJECTED->value,
            'rejection_reason' => $reason,
            'processed_by' => $processedBy,
            'processed_at' => now(),
        ]);
    }

    /**
     * Complete the order
     */
    public function complete(array $completionData = null)
    {
        $this->update([
            'status' => OrderStatus::COMPLETED->value,
            'completed_at' => now(),
            'completion_data' => $completionData,
        ]);
    }

    /**
     * Cancel the order
     */
    public function cancel()
    {
        $this->update([
            'status' => OrderStatus::CANCELLED->value,
        ]);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    /**
     * Get order type label
     */
    public function getOrderTypeLabelAttribute(): string
    {
        return $this->order_type->label();
    }
}

