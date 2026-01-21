<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleMake extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'status',
    ];

    public function vehicleModels(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }

    // Alias for convenience
    public function models(): HasMany
    {
        return $this->vehicleModels();
    }

    /**
     * Get the leasing cars for this make
     */
    public function leasingCars(): HasMany
    {
        return $this->hasMany(LeasingCar::class, 'vehicle_make_id');
    }

    /**
     * Scope for active makes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
