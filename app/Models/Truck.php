<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Truck extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'origin',
        'registration_number',
        'condition',
        'vehicle_make_id',
        'vehicle_model_id',
        'variant',
        'year',
        'truck_type',
        'body_type',
        'fuel_type',
        'transmission',
        'engine_capacity',
        'engine_cc',
        'drive_type',
        'color_exterior',
        'color_interior',
        'doors',
        'seats',
        'mileage',
        'vin',
        'cargo_capacity_kg',
        'towing_capacity_kg',
        'payload_capacity_kg',
        'bed_length_m',
        'bed_width_m',
        'axle_configuration',
        'price',
        'currency',
        'original_price',
        'negotiable',
        'features',
        'safety_features',
        'additional_specs',
        'image_front',
        'image_side',
        'image_back',
        'other_images',
        'documents',
        'entity_id',
        'registered_by',
        'status',
        'notes',
        'approved_at',
        'approved_by',
        'sold_at',
    ];

    protected $casts = [
        'year' => 'integer',
        'engine_cc' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'mileage' => 'integer',
        'cargo_capacity_kg' => 'decimal:2',
        'towing_capacity_kg' => 'decimal:2',
        'payload_capacity_kg' => 'decimal:2',
        'bed_length_m' => 'decimal:2',
        'bed_width_m' => 'decimal:2',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'negotiable' => 'boolean',
        'features' => 'array',
        'safety_features' => 'array',
        'additional_specs' => 'array',
        'other_images' => 'array',
        'documents' => 'array',
        'approved_at' => 'datetime',
        'sold_at' => 'datetime',
        'status' => VehicleStatus::class,
    ];

    /**
     * Get the make of the truck
     */
    public function make(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    /**
     * Get the model of the truck
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    /**
     * Get the entity (dealer/owner) of the truck
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the user who registered the truck
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the user who approved the truck
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get full name (Make Model Year)
     */
    public function getFullNameAttribute(): string
    {
        $parts = [];
        if ($this->year) $parts[] = $this->year;
        if ($this->make) $parts[] = $this->make->name;
        if ($this->model) $parts[] = $this->model->name;
        if ($this->variant) $parts[] = $this->variant;
        
        return implode(' ', $parts);
    }

    /**
     * Scope: Only pending trucks
     */
    public function scopePending($query)
    {
        return $query->where('status', VehicleStatus::PENDING->value);
    }

    /**
     * Scope: Only approved trucks
     */
    public function scopeApproved($query)
    {
        return $query->where('status', VehicleStatus::APPROVED->value);
    }

    /**
     * Scope: Only active trucks (approved, not sold, not removed)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            VehicleStatus::APPROVED->value,
            VehicleStatus::HOLD->value,
        ]);
    }
}
