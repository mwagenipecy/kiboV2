<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GarageServiceOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'agent_id',
        'service_type',
        'vehicle_make',
        'vehicle_model',
        'vehicle_year',
        'vehicle_registration',
        'booking_type',
        'scheduled_date',
        'scheduled_time',
        'service_description',
        'customer_notes',
        'status',
        'processed_by',
        'processed_at',
        'rejection_reason',
        'quoted_price',
        'currency',
        'quotation_notes',
        'quoted_at',
        'completed_at',
        'completion_notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime',
        'quoted_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'quoted_price' => 'decimal:2',
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
            $number = 'GS-' . strtoupper(uniqid());
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
     * Get the agent/garage for this order
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Get the user who processed this order
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
