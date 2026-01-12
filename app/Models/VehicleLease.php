<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleLease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vehicle_id',
        'entity_id',
        // Vehicle Information
        'vehicle_title',
        'vehicle_year',
        'vehicle_make',
        'vehicle_model',
        'vehicle_variant',
        'body_type',
        'fuel_type',
        'transmission',
        'engine_capacity',
        'mileage',
        'condition',
        'color_exterior',
        'seats',
        'image_front',
        'image_side',
        'image_back',
        'other_images',
        'features',
        'vehicle_description',
        // Lease Information
        'lease_title',
        'lease_description',
        'monthly_payment',
        'lease_term_months',
        'down_payment',
        'security_deposit',
        'mileage_limit_per_year',
        'excess_mileage_charge',
        'acquisition_fee',
        'disposition_fee',
        'maintenance_included',
        'insurance_included',
        'min_credit_score',
        'min_monthly_income',
        'min_age',
        'additional_requirements',
        'purchase_option_available',
        'residual_value',
        'early_termination_fee',
        'status',
        'is_featured',
        'priority',
        'available_from',
        'available_until',
        'terms_conditions',
        'included_services',
        'notes',
    ];

    protected $casts = [
        'monthly_payment' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'excess_mileage_charge' => 'decimal:2',
        'acquisition_fee' => 'decimal:2',
        'disposition_fee' => 'decimal:2',
        'min_monthly_income' => 'decimal:2',
        'residual_value' => 'decimal:2',
        'early_termination_fee' => 'decimal:2',
        'maintenance_included' => 'boolean',
        'insurance_included' => 'boolean',
        'purchase_option_available' => 'boolean',
        'is_featured' => 'boolean',
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'terms_conditions' => 'array',
        'included_services' => 'array',
        'other_images' => 'array',
        'features' => 'array',
    ];

    /**
     * Get the vehicle associated with this lease
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the entity (dealer) offering this lease
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Scope for active leases
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('available_from')
                  ->orWhere('available_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('available_until')
                  ->orWhere('available_until', '>=', now());
            });
    }

    /**
     * Scope for featured leases
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->orderBy('priority', 'desc');
    }

    /**
     * Scope by entity
     */
    public function scopeForEntity($query, $entityId)
    {
        return $query->where('entity_id', $entityId);
    }

    /**
     * Check if lease is currently available
     */
    public function isAvailable(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->available_from && $this->available_from->gt($now)) {
            return false;
        }

        if ($this->available_until && $this->available_until->lt($now)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate total upfront cost
     */
    public function getTotalUpfrontCostAttribute(): float
    {
        return $this->down_payment + $this->security_deposit + $this->acquisition_fee;
    }

    /**
     * Calculate total lease cost
     */
    public function getTotalLeaseCostAttribute(): float
    {
        return ($this->monthly_payment * $this->lease_term_months) + $this->total_upfront_cost;
    }

    /**
     * Get monthly payment with services
     */
    public function getMonthlyPaymentWithServicesAttribute(): float
    {
        return $this->monthly_payment;
    }
}
