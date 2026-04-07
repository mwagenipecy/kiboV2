<?php

namespace App\Models;

use App\Enums\EntityStatus;
use App\Enums\EntityType;
use App\Enums\VehicleStatus;
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
        $plan = $this->relationLoaded('pricingPlan') ? $this->pricingPlan : $this->pricingPlan()->first();

        return $plan?->max_listings ?? PricingPlan::activeCarsFreeTier()?->max_listings;
    }

    /**
     * Max number of active truck listings allowed (from plan or free tier)
     */
    public function getMaxActiveTrucksAttribute(): ?int
    {
        $plan = $this->relationLoaded('pricingPlan') ? $this->pricingPlan : $this->pricingPlan()->first();

        return $plan?->max_trucks ?? PricingPlan::activeCarsFreeTier()?->max_trucks;
    }

    /**
     * Max cars allowed (assigned plan, else active Free tier row in advertising_pricing, else config).
     */
    public function getMaxAllowedCarsAttribute(): int
    {
        $plan = $this->relationLoaded('pricingPlan') ? $this->pricingPlan : $this->pricingPlan()->first();
        if ($this->pricing_plan_id && $plan !== null && $plan->max_listings !== null) {
            return (int) $plan->max_listings;
        }

        $free = PricingPlan::activeCarsFreeTier();
        if ($free && $free->max_listings !== null) {
            return (int) $free->max_listings;
        }

        return (int) config('kibo.free_max_cars', 6);
    }

    /**
     * Max trucks allowed (assigned plan, else Free tier row, else config).
     */
    public function getMaxAllowedTrucksAttribute(): int
    {
        $plan = $this->relationLoaded('pricingPlan') ? $this->pricingPlan : $this->pricingPlan()->first();
        if ($this->pricing_plan_id && $plan !== null && $plan->max_trucks !== null) {
            return (int) $plan->max_trucks;
        }

        $free = PricingPlan::activeCarsFreeTier();
        if ($free && $free->max_trucks !== null) {
            return (int) $free->max_trucks;
        }

        return (int) config('kibo.free_max_trucks', 1);
    }

    /**
     * Max lease listings allowed (assigned plan, else Free tier row, else config).
     */
    public function getMaxAllowedLeasesAttribute(): int
    {
        $plan = $this->relationLoaded('pricingPlan') ? $this->pricingPlan : $this->pricingPlan()->first();
        if ($this->pricing_plan_id && $plan !== null && $plan->max_leases !== null) {
            return (int) $plan->max_leases;
        }

        $free = PricingPlan::activeCarsFreeTier();
        if ($free && $free->max_leases !== null) {
            return (int) $free->max_leases;
        }

        return (int) config('kibo.free_max_leases', 1);
    }

    /**
     * Dealer pays for cars advertising (show dealer contact on listings). Free tier does not count as paid.
     */
    public function hasPaidCarsAdvertisingPlan(): bool
    {
        if (! $this->pricing_plan_id) {
            return false;
        }

        $plan = $this->relationLoaded('pricingPlan') ? $this->pricingPlan : $this->pricingPlan()->first();

        return $plan !== null && $plan->isBillable();
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
