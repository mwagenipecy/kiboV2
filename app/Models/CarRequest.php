<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'public_token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'vehicle_make_id',
        'vehicle_model_id',
        'min_year',
        'max_year',
        'min_budget',
        'max_budget',
        'fuel_type',
        'transmission',
        'body_type',
        'color',
        'location',
        'notes',
        'status',
        'accepted_offer_id',
    ];

    protected $casts = [
        'min_year' => 'integer',
        'max_year' => 'integer',
        'min_budget' => 'integer',
        'max_budget' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function make(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class, 'vehicle_make_id');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(DealerCarOffer::class);
    }
}


