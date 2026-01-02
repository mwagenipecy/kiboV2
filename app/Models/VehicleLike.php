<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'user_id',
    ];

    /**
     * Get the vehicle that was liked
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who liked the vehicle
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
