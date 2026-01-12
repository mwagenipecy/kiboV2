<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasingCar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'registration_number',
        'condition',
        'vehicle_make_id',
        'vehicle_model_id',
        'variant',
        'year',
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
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'security_deposit',
        'currency',
        'negotiable',
        'min_lease_days',
        'max_lease_days',
        'mileage_limit_per_day',
        'excess_mileage_charge',
        'min_driver_age',
        'insurance_included',
        'fuel_included',
        'lease_terms',
        'features',
        'safety_features',
        'additional_specs',
        'image_front',
        'image_side',
        'image_back',
        'image_interior',
        'other_images',
        'documents',
        'entity_id',
        'registered_by',
        'status',
        'notes',
        'approved_at',
        'approved_by',
        'total_leases',
        'view_count',
    ];

    protected $casts = [
        'features' => 'array',
        'safety_features' => 'array',
        'additional_specs' => 'array',
        'other_images' => 'array',
        'documents' => 'array',
        'approved_at' => 'datetime',
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'mileage_limit_per_day' => 'decimal:2',
        'excess_mileage_charge' => 'decimal:2',
        'insurance_included' => 'boolean',
        'fuel_included' => 'boolean',
        'negotiable' => 'boolean',
    ];

    /**
     * Get the vehicle make
     */
    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    /**
     * Get the vehicle model
     */
    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    /**
     * Get the entity that owns this leasing car
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the user who registered this leasing car
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * Get the user who approved this leasing car
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for available cars
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for leased cars
     */
    public function scopeLeased($query)
    {
        return $query->where('status', 'leased');
    }

    /**
     * Scope for pending approval
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('view_count');
    }

    /**
     * Calculate weekly rate if not set
     */
    public function getWeeklyRateAttribute($value)
    {
        return $value ?? ($this->daily_rate * 7 * 0.85); // 15% discount
    }

    /**
     * Calculate monthly rate if not set
     */
    public function getMonthlyRateAttribute($value)
    {
        return $value ?? ($this->daily_rate * 30 * 0.70); // 30% discount
    }

    /**
     * Get formatted daily rate
     */
    public function getFormattedDailyRateAttribute()
    {
        return number_format($this->daily_rate, 2);
    }

    /**
     * Get formatted security deposit
     */
    public function getFormattedSecurityDepositAttribute()
    {
        return number_format($this->security_deposit, 2);
    }

    /**
     * Check if car is available for lease
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if car is currently leased
     */
    public function isLeased(): bool
    {
        return $this->status === 'leased';
    }

    /**
     * Get all images as array
     */
    public function getAllImagesAttribute()
    {
        $images = [];
        
        if ($this->image_front) $images[] = $this->image_front;
        if ($this->image_side) $images[] = $this->image_side;
        if ($this->image_back) $images[] = $this->image_back;
        if ($this->image_interior) $images[] = $this->image_interior;
        
        if ($this->other_images && is_array($this->other_images)) {
            $images = array_merge($images, $this->other_images);
        }
        
        return $images;
    }
}
