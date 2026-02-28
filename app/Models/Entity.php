<?php

namespace App\Models;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Enums\VehicleStatus;
use App\Models\PricingPlan;
use App\Models\Vehicle;
use App\Models\Truck;
use App\Models\VehicleLease;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'registration_number',
        'tax_id',
        'website',
        'description',
        'metadata',
        'pricing_plan_id',
    ];

    protected $casts = [
        'type' => EntityType::class,
        'status' => EntityStatus::class,
        'metadata' => 'array',
    ];

    /**
     * Subscription bundle (pricing plan) – defines max active car listings for dealers
     */
    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class, 'pricing_plan_id');
    }

    /**
     * Max number of active car listings allowed (from plan or free tier)
     */
    public function getMaxActiveListingsAttribute(): ?int
    {
        return $this->pricingPlan?->max_listings;
    }

    /**
     * Max number of active truck listings allowed (from plan or free tier)
     */
    public function getMaxActiveTrucksAttribute(): ?int
    {
        return $this->pricingPlan?->max_trucks;
    }

    /**
     * Max cars allowed (paid plan or free tier limit)
     */
    public function getMaxAllowedCarsAttribute(): int
    {
        $fromPlan = $this->pricingPlan?->max_listings;
        return $fromPlan !== null ? (int) $fromPlan : (int) config('kibo.free_max_cars', 3);
    }

    /**
     * Max trucks allowed (paid plan or free tier limit)
     */
    public function getMaxAllowedTrucksAttribute(): int
    {
        $fromPlan = $this->pricingPlan?->max_trucks;
        return $fromPlan !== null ? (int) $fromPlan : (int) config('kibo.free_max_trucks', 1);
    }

    /**
     * Max lease listings allowed (paid plan or free tier limit)
     */
    public function getMaxAllowedLeasesAttribute(): int
    {
        $fromPlan = $this->pricingPlan?->max_leases;
        return $fromPlan !== null ? (int) $fromPlan : (int) config('kibo.free_max_leases', 1);
    }

    /**
     * Count vehicles (cars) with any status except sold
     */
    public function vehiclesCountExcludingSold(): int
    {
        return Vehicle::where('entity_id', $this->id)
            ->where('status', '!=', VehicleStatus::SOLD)
            ->count();
    }

    /**
     * Count trucks with any status except sold
     */
    public function trucksCountExcludingSold(): int
    {
        return Truck::where('entity_id', $this->id)
            ->where('status', '!=', VehicleStatus::SOLD)
            ->count();
    }

    /**
     * Whether this entity can add another car (under limit)
     */
    public function canAddVehicle(): bool
    {
        return $this->vehiclesCountExcludingSold() < $this->max_allowed_cars;
    }

    /**
     * Whether this entity can add another truck (under limit)
     */
    public function canAddTruck(): bool
    {
        return $this->trucksCountExcludingSold() < $this->max_allowed_trucks;
    }

    /**
     * Count lease listings (vehicle_leases) for this entity
     */
    public function leasesCount(): int
    {
        return VehicleLease::where('entity_id', $this->id)->count();
    }

    /**
     * Whether this entity can add another lease listing (under limit)
     */
    public function canAddLease(): bool
    {
        return $this->leasesCount() < $this->max_allowed_leases;
    }

    /**
     * Get the users for the entity
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the primary user (first user) of the entity
     */
    public function primaryUser()
    {
        return $this->users()->oldest()->first();
    }

    /**
     * Scope to filter by entity type
     */
    public function scopeOfType($query, EntityType $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status
     */
    public function scopeWithStatus($query, EntityStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status->badgeClass();
    }

    /**
     * Get the type label
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }
}

