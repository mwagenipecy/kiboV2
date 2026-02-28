<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SparePartOrder extends Model
{
    protected $fillable = [
        'order_number',
        'order_channel',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'vehicle_make_id',
        'vehicle_model_id',
        'condition',
        'part_name',
        'description',
        'images',
        'delivery_address',
        'delivery_city',
        'delivery_region',
        'delivery_country',
        'delivery_postal_code',
        'delivery_latitude',
        'delivery_longitude',
        'contact_name',
        'contact_phone',
        'contact_email',
        'status',
        'admin_notes',
        'assigned_to',
        'quoted_price',
        'currency',
        'chat_messages',
        // Agent assignment
        'agent_id',
        'accepted_quotation_id',
        // Quotation fields
        'quotation_notes',
        'quoted_at',
        // User confirmation
        'user_confirmed_at',
        'user_accepted_quote',
        // Delivery information
        'estimated_delivery_date',
        'delivery_notes',
        'delivery_confirmed_at',
        // Payment information
        'payment_method',
        'payment_account_details',
        'payment_proof',
        'payment_notes',
        'payment_submitted_at',
        'payment_verified',
        'payment_verified_at',
        // Final delivery
        'shipped_at',
        'tracking_number',
        'delivered_at',
    ];

    protected $casts = [
        'images' => 'array',
        'chat_messages' => 'array',
        'payment_account_details' => 'array',
        'quoted_price' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
        'quoted_at' => 'datetime',
        'user_confirmed_at' => 'datetime',
        'user_accepted_quote' => 'boolean',
        'estimated_delivery_date' => 'date',
        'delivery_confirmed_at' => 'datetime',
        'payment_submitted_at' => 'datetime',
        'payment_verified' => 'boolean',
        'payment_verified_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'SPO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleMake(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class);
    }

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(SparePartQuotation::class);
    }

    public function acceptedQuotation(): BelongsTo
    {
        return $this->belongsTo(SparePartQuotation::class, 'accepted_quotation_id');
    }

    public function pendingQuotations(): HasMany
    {
        return $this->hasMany(SparePartQuotation::class)->where('status', 'pending');
    }

    /**
     * Check if order is open for quotations
     */
    public function isOpenForQuotations(): bool
    {
        return $this->status === 'pending' && !$this->accepted_quotation_id;
    }

    /**
     * Get the count of quotations received
     */
    public function getQuotationCountAttribute(): int
    {
        return $this->quotations()->count();
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'quoted' => 'purple',
            'accepted' => 'green',
            'rejected' => 'red',
            'awaiting_payment' => 'orange',
            'payment_submitted' => 'indigo',
            'payment_verified' => 'teal',
            'preparing' => 'cyan',
            'shipped' => 'blue',
            'delivered' => 'green',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'processing' => 'Processing',
            'quoted' => 'Quotation Sent',
            'accepted' => 'Quote Accepted',
            'rejected' => 'Rejected',
            'awaiting_payment' => 'Awaiting Payment',
            'payment_submitted' => 'Payment Submitted',
            'payment_verified' => 'Payment Verified',
            'preparing' => 'Preparing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status ?? 'Unknown'),
        };
    }

    /**
     * Check if order can be quoted
     */
    public function canBeQuoted(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Check if user can confirm quote
     */
    public function canUserConfirmQuote(): bool
    {
        return $this->status === 'quoted' && $this->quoted_price > 0;
    }

    /**
     * Check if agent can set delivery info
     */
    public function canSetDeliveryInfo(): bool
    {
        return in_array($this->status, ['accepted', 'awaiting_payment']);
    }

    /**
     * Check if user can submit payment
     */
    public function canSubmitPayment(): bool
    {
        return $this->status === 'awaiting_payment';
    }

    /**
     * Check if agent can verify payment
     */
    public function canVerifyPayment(): bool
    {
        return $this->status === 'payment_submitted';
    }
}
