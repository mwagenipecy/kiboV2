<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleModel extends Model
{
    protected $fillable = [
        'name',
        'vehicle_make_id',
        'status',
    ];

    public function vehicleMake(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class);
    }
}
