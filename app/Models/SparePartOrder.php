<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePartOrder extends Model
{
    protected $fillable = [
        'order_number',
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
    ];

    protected $casts = [
        'images' => 'array',
        'chat_messages' => 'array',
        'quoted_price' => 'decimal:2',
        'delivery_latitude' => 'decimal:8',
        'delivery_longitude' => 'decimal:8',
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
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
