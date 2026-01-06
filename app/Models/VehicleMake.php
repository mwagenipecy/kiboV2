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
}
